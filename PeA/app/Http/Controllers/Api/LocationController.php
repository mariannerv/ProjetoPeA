<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Location;
use MongoDB\BSON\ObjectId;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    protected $connection = 'mongodb';
    protected $collection = 'location'; 

    public function fetchLocationAddress(Request $request)
    {
        try {
            $request->validate([
                'locationId' => 'required|string',
            ]);

            $locationId = new ObjectId($request->locationId);

            $location = Location::where('_id', $locationId)->first();

            if ($location) {
                $address = $location->rua . ', ' . $location->freguesia . ', ' . $location->municipio . ', ' . $location->distrito . ', ' . $location->codigo_postal . ', ' . $location->pais;

                return response()->json([
                    "status" => true,
                    "data" => $address,
                    "code" => 200,
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Location not found.",
                    "code" => 404,
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "An error occurred while retrieving location information.",
                "code" => 500,
            ], 500);
        }
    }

    public function getAllLocations()
    {
        try {
            $locations = Location::all();
            return response()->json($locations);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function viewLocation(Request $request, $_id)
{
    try {
        $location = Location::find($_id);

        if ($location) {
            return response()->json([
                "status" => true,
                "data" => $location,
                "code" => 200,
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Location not found.",
                "code" => 404,
            ], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            "status" => false,
            "message" => "An error occurred while retrieving location information.",
            "code" => 500,
        ], 500);
    }
}


}
