<?php

namespace App\Listeners;

use App\Events\AirportImportUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Interfaces\CityServiceInterface;
use App\Services\Interfaces\AirportServiceInterface;
use App\Services\Interfaces\Neo4jServiceInterface;
use Illuminate\Support\Facades\Log;
use Storage;
use Illuminate\Support\Facades\Validator;

class ProcessAirportImport implements ShouldQueue
{
    protected $cityService, $airportService, $neo4jService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(CityServiceInterface $cityService, AirportServiceInterface $airportService, Neo4jServiceInterface $neo4jService)
    {
        $this->cityService = $cityService;
        $this->airportService = $airportService;
        $this->neo4jService = $neo4jService;
    }

    /**
     * Handle the event.
     *
     * @param  AirportImportUploaded  $event
     * @return void
     */
    public function handle(AirportImportUploaded $event)
    {
        $filename = $event->filename;
        Log::channel('airports')->info($filename.': Started processing.');
        $file = Storage::path('private/airports-import/'.$filename);
        $columns = ['id', 'name', 'city', 'country', 'iata', 'icao', 'lat', 'long', 'altitude', 'timezone', 'dst', 'tz', 'type', 'source'];
        $columnsCount = count($columns);
        $handle = fopen($file, "r");
        $array = [];
        $count = 0;
        $lineCount = 0;
        $insertChunkSize = 1000;
        
        $this->neo4jService->addUniqueConstraint('airportUniqueId', 'Airport', 'id');

        if($handle) {
            while(!feof($handle)) {
                $line = fgetcsv($handle, 0, ',');
                $lineCount++;
                if($line) {
                    $combined = array_combine($columns, $line);

                    if(!$combined) {
                        Log::channel('airports')->info($filename.': Line '.$lineCount.' - does not have valid columns.');
                        continue;
                    }

                    foreach ($combined as $key => $value) $combined[$key] = trim($value) === "\N" || trim($value) == "" ? null : trim($value);

                    $validator = $this->validateLine($combined);

                    if($validator->fails()) {
                        Log::channel('airports')->info($filename.': Line '.$lineCount.' - contains invalid data. | '.implode(' / ', $validator->messages()->all()));
                        continue;
                    }

                    $combined['city_id'] = $this->cityService->getCityIdByNameAndCountry($combined['city'], $combined['country']);
                    if(!$combined['city_id']) {
                        Log::channel('airports')->info($filename.': Line '.$lineCount.' - city does not exist.');
                        continue;
                    }

                    unset($combined['city']);
                    unset($combined['country']);
                    $combined['synced'] = 0;
                    $array[] = $combined;
                    $count++;
                    
                    if ($count === $insertChunkSize) {
                        $this->airportService->chunkInsert($array);
                        $this->airportService->chunk(1000, function($airports) {
                            $this->neo4jService->insertAirports($airports);
                            $airports->each->update(['synced' => 1]);
                        });
                        $array = [];
                        $count = 0;
                    }
                }
            }
            
            if (count($array)) {
                $this->airportService->chunkInsert($array);
                $this->airportService->chunk(1000, function($airports) {
                    $this->neo4jService->insertAirports($airports);
                    $airports->each->update(['synced' => 1]);
                });
            }
        }

        Storage::delete('private/airports-import/'.$filename);
        Log::channel('airports')->info($filename.': Finished processing.');
    }

    private function validateLine($array) {
        return Validator::make($array, [
            'id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'iata' => ['nullable', 'string', 'max:3'],
            'icao' => ['nullable', 'string', 'max:4'],
            'lat' => ['nullable', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'long' => ['nullable', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'altitude' => ['nullable', 'numeric'],
            'timezone' => ['nullable', 'numeric'],
            'dst' => ['nullable', 'string', 'max:1', 'in:E,A,S,O,Z,N,U'],
            'tz' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
