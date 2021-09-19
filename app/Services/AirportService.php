<?php

namespace App\Services;

use App\Repositories\Interfaces\AirportRepositoryInterface;
use App\Services\Interfaces\AirportServiceInterface;
use Storage;
use Illuminate\Support\Str;
use App\Events\AirportImportUploaded;

class AirportService implements AirportServiceInterface
{
    protected $airportRepository;

    public function __construct(AirportRepositoryInterface $airportRepository)
    {
        $this->airportRepository = $airportRepository;
    }

    public function saveFile($file)
    {
        $filename = time().'-'.Str::random(20).'.'.$file->getClientOriginalExtension();
        
        while(Storage::exists('private/airports-import/'.$filename))
            $filename = time().'-'.Str::random(20).'.'.$file->getClientOriginalExtension();

        $file->storeAs('private/airports-import', $filename);

        event(new AirportImportUploaded($filename));

        return ['message' => 'File uploaded. Check airports.log for more details.', 'filename' => $filename];
    }

    public function existsById($id)
    {
        return $this->airportRepository->existsById($id);
    }

    public function chunkInsert($array)
    {
        return $this->airportRepository->chunkInsert($array);
    }

    public function chunk($count, $callback)
    {
        $this->airportRepository->chunk($count, $callback);
    }
}