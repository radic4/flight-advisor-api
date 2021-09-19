<?php

namespace App\Listeners;

use App\Events\RouteImportUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Interfaces\AirportServiceInterface;
use App\Services\Interfaces\RouteServiceInterface;
use App\Services\Interfaces\Neo4jServiceInterface;
use Illuminate\Support\Facades\Log;
use Storage;
use Illuminate\Support\Facades\Validator;

class ProcessRouteImport implements ShouldQueue
{
    protected $airportService, $routeService, $neo4jService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(AirportServiceInterface $airportService, RouteServiceInterface $routeService, Neo4jServiceInterface $neo4jService)
    {
        $this->airportService = $airportService;
        $this->routeService = $routeService;
        $this->neo4jService = $neo4jService;
    }

    /**
     * Handle the event.
     *
     * @param  RouteImportUploaded  $event
     * @return void
     */
    public function handle(RouteImportUploaded $event)
    {
        $filename = $event->filename;
        Log::channel('routes')->info($filename.': Started processing.');
        $file = Storage::path('private/routes-import/'.$filename);
        $columns = ['airline', 'airline_id', 'source_airport', 'source_airport_id', 'destination_airport', 'destination_airport_id', 'codeshare', 'stops', 'equipment', 'price'];
        $columnsCount = count($columns);
        $handle = fopen($file, "r");
        $array = [];
        $count = 0;
        $lineCount = 0;
        $insertChunkSize = 1000;
        
        if($handle) {
            while(!feof($handle)) {
                $line = fgetcsv($handle, 0, ',');
                $lineCount++;
                if($line) {
                    $combined = array_combine($columns, $line);

                    if(!$combined) {
                        Log::channel('routes')->info($filename.': Line '.$lineCount.' - does not have valid columns.');
                        continue;
                    }

                    foreach ($combined as $key => $value) $combined[$key] = trim($value) === "\N" || trim($value) == "" ? null : trim($value);

                    $validator = $this->validateLine($combined);

                    if($validator->fails()) {
                        Log::channel('routes')->info($filename.': Line '.$lineCount.' - contains invalid data. | '.implode(' / ', $validator->messages()->all()));
                        continue;
                    }

                    if(!$this->airportService->existsById($combined['source_airport_id']) || !$this->airportService->existsById($combined['destination_airport_id'])) {
                        Log::channel('routes')->info($filename.': Line '.$lineCount.' - airport does not exist.');
                        continue;
                    }

                    unset($combined['source_airport']);
                    unset($combined['destination_airport']);
                    $combined['synced'] = 0;
                    $array[] = $combined;
                    $count++;
                    
                    if ($count === $insertChunkSize) {
                        $this->routeService->chunkInsert($array);
                        $this->routeService->chunk(1000, function($routes) {
                            $this->neo4jService->insertRoutes($routes);
                            $routes->each->update(['synced' => 1]);
                        });
                        $array = [];
                        $count = 0;
                    }
                }
            }
            
            if (count($array)) {
                $this->routeService->chunkInsert($array);
                $this->routeService->chunk(1000, function($routes) {
                    $this->neo4jService->insertRoutes($routes);
                    $routes->each->update(['synced' => 1]);
                });
            }
        }

        Storage::delete('private/routes-import/'.$filename);
        Log::channel('routes')->info($filename.': Finished processing.');
    }

    private function validateLine($array) {
        return Validator::make($array, [
            'airline' => ['required', 'string', 'max:3'],
            'airline_id' => ['required', 'integer'],
            'source_airport' => ['required', 'string', 'max:4'],
            'source_airport_id' => ['required', 'integer', 'different:destination_airport_id'],
            'destination_airport' => ['required', 'string', 'max:4'],
            'destination_airport_id' => ['required', 'integer'],
            'codeshare' => ['nullable', 'string', 'max:1', 'in:Y'],
            'stops' => ['nullable', 'integer', 'min:0'],
            'equipment' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric']
        ]);
    }
}
