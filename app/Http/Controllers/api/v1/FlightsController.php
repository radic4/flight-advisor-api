<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheapestFlightRequest;
use App\Services\Interfaces\FlightServiceInterface;

class FlightsController extends Controller
{
    protected $flightService;

    public function __construct(FlightServiceInterface $flightService)
    {
        $this->flightService = $flightService;
    }

    public function cheapestFlight(CheapestFlightRequest $request)
    {
        return response()->json($this->flightService->findCheapest($request->from_city_id, $request->to_city_id), 200);
    }
}
