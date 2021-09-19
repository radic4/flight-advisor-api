<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\RouteServiceInterface;
use App\Http\Requests\RouteImportRequest;

class RoutesController extends Controller
{
    protected $routeService;

    public function __construct(RouteServiceInterface $routeService)
    {
        $this->routeService = $routeService;
    }

    public function import(RouteImportRequest $request)
    {
        if(strtolower($request->file->getClientOriginalExtension()) != 'txt') return response()->json(['message' => 'Uploaded file must be text file.'], 400);

        return response()->json($this->routeService->saveFile($request->file), 200);
    }
}
