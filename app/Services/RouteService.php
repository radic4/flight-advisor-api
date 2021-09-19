<?php

namespace App\Services;

use App\Repositories\Interfaces\RouteRepositoryInterface;
use App\Services\Interfaces\RouteServiceInterface;
use Storage;
use Illuminate\Support\Str;
use App\Events\RouteImportUploaded;

class RouteService implements RouteServiceInterface
{
    protected $routeRepository;

    public function __construct(RouteRepositoryInterface $routeRepository)
    {
        $this->routeRepository = $routeRepository;
    }

    public function saveFile($file)
    {
        $filename = time().'-'.Str::random(20).'.'.$file->getClientOriginalExtension();
        
        while(Storage::exists('private/routes-import/'.$filename))
            $filename = time().'-'.Str::random(20).'.'.$file->getClientOriginalExtension();

        $file->storeAs('private/routes-import', $filename);

        event(new RouteImportUploaded($filename));

        return ['message' => 'File uploaded. Check routes.log for more details.', 'filename' => $filename];
    }

    public function chunkInsert($array)
    {
        return $this->routeRepository->chunkInsert($array);
    }

    public function chunk($count, $callback)
    {
        $this->routeRepository->chunk($count, $callback);
    }
}