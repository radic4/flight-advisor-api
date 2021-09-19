<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\AirportServiceInterface;
use App\Http\Requests\AirportImportRequest;

class AirportsController extends Controller
{
    protected $airportService;

    public function __construct(AirportServiceInterface $airportService)
    {
        $this->airportService = $airportService;
    }

    public function import(AirportImportRequest $request)
    {
        if(strtolower($request->file->getClientOriginalExtension()) != 'txt') return response()->json(['message' => 'Uploaded file must be text file.'], 400);

        return response()->json($this->airportService->saveFile($request->file), 200);
    }
}
