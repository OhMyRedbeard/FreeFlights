<?php

namespace Modules\FreeFlights\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\Bid;
use App\Models\Enums\FlightType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FreeFlightsController extends Controller
{
    /**
     * Display the free flights creation page
     * @return Renderable
     */
    public function index()
    {
        $airlines = Airline::orderBy('name')->get();
        $airports = Airport::orderBy('icao')->get();
        $flightTypes = FlightType::labels();
        
        return view('freeflights::index', compact('airlines', 'airports', 'flightTypes'));
    }

    /**
     * Create a new free flight and add to user bids
     */
    public function create(Request $request)
    {
        $request->merge([
            'flight_type' => (int) $request->flight_type
        ]);

        $request->validate([
            'airline_id' => 'required|exists:airlines,id',
            'flight_number' => 'required|string|max:10',
            'flight_type' => 'required|integer',
            'dpt_airport_id' => 'required|exists:airports,id',
            'arr_airport_id' => 'required|exists:airports,id|different:dpt_airport_id',
        ]);

        try {
            $dptAirport = Airport::find($request->dpt_airport_id);
            $arrAirport = Airport::find($request->arr_airport_id);
            
            $distance = $this->calculateDistance(
                $dptAirport->lat, 
                $dptAirport->lon, 
                $arrAirport->lat, 
                $arrAirport->lon
            );

            $flight = new Flight();
            $flight->airline_id = $request->airline_id;
            $flight->flight_number = $request->flight_number;
            $flight->flight_type = $request->flight_type;
            $flight->dpt_airport_id = $request->dpt_airport_id;
            $flight->arr_airport_id = $request->arr_airport_id;
            $flight->distance = round($distance);
            $flight->save();

            // Add to user's bids
            $bid = new Bid();
            $bid->user_id = Auth::id();
            $bid->flight_id = $flight->id;
            $bid->save();

            return redirect()->route('freeflights.index')
                ->with('success', 'Free flight created successfully and added to your bids!');

        } catch (\Exception $e) {
            Log::error('Error creating free flight: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'Error creating flight: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Generate a random flight number
     */
    public function generateFlightNumber()
    {
        $number = rand(1, 9999);
        $suffix = '';
        
        // Add alphabetic suffix only for numbers 1-999
        if ($number <= 999) {
            $letters = range('A', 'Z');
            $suffixLength = rand(1, 2);
            
            for ($i = 0; $i < $suffixLength; $i++) {
                $suffix .= $letters[array_rand($letters)];
            }
        }
        
        return response()->json(['flight_number' => $number . $suffix]);
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     * @param float $lat1 Latitude of first point
     * @param float $lon1 Longitude of first point
     * @param float $lat2 Latitude of second point
     * @param float $lon2 Longitude of second point
     * @return float Distance in nautical miles
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 3440.065; // Earth's radius in nautical miles
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
}
