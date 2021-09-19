<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Http\Requests\StoreCityRequest;
use App\Services\Interfaces\CityServiceInterface;

class CitiesController extends Controller
{
    protected $cityService;

    public function __construct(CityServiceInterface $cityService)
    {
        $this->cityService = $cityService;
    }

    public function store(StoreCityRequest $request)
    {
        return response()->json($this->cityService->store($request->name, $request->country, $request->description), 201);
    }

    public function getSearchedCitiesWithComments(CityRequest $request)
    {
        return response()->json($this->cityService->getSearchedCitiesWithComments($request->search, $request->comments), 200);
    }
}
