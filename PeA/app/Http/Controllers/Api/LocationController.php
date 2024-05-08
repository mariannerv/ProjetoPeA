<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Location;
use MongoDB\BSON\ObjectId;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    protected $connection = 'mongodb';
    protected $collection = 'location'; 


    public function viewLocation(Request $request)
{
    try {
        $request->validate([
            '_id' => 'required|string',
        ]);

        $location = Location::find($request->_id);

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

public function fetchLocationAddress($id)
{
    try {
        $location = Location::where('_id', $id)->first();
        if ($location) {
            $addressComponents = [];
            if (!empty(trim($location->rua))) {
                $addressComponents[] = str_replace(' ', '%20', $location->rua);
            }// freguesia n funciona, o tomtom n reconhece
            /* 
            if (!empty(trim($location->freguesia))) {
                $addressComponents[] = str_replace(' ', '%20', $location->freguesia);
            }*/
            if (!empty(trim($location->municipio))) {
                $addressComponents[] = str_replace(' ', '%20', $location->municipio);
            }
            if (!empty(trim($location->distrito))) {
                $addressComponents[] = str_replace(' ', '%20', $location->distrito);
            }
            if (!empty(trim($location->codigo_postal))) {
                $addressComponents[] = str_replace(' ', '%20', $location->codigo_postal);
            }
            if (!empty(trim($location->pais))) {
                $addressComponents[] = str_replace(' ', '%20', $location->pais);
            }
            
            $encodedAddress = implode('%20', $addressComponents);
            
            $apiKey = "YaHwXWGyliPES0fF3ymLjwaqwdo2IbZn";
            
            return response()->json([
                "status" => true,
                "data" => [$encodedAddress, $apiKey],
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

        return response()->json([
            "status" => true,
            "data" => $locations,
            "code" => 200,
        ]);
    } catch (\Exception $e) {
        // Log the exception
        \Log::error("Error fetching all locations: " . $e->getMessage());

        return response()->json([
            "status" => false,
            "message" => "An error occurred while fetching all locations.",
            "code" => 500,
        ], 500);
    }
}


    public function registerLocation(Request $request)
    {
        try {
            $request->validate([
                'rua' => 'required|string',
                'freguesia' => 'required|string',
                'municipio' => 'required|string',
                'distrito' => 'required|string',
                'codigo_postal' => 'required|string',
                'pais' => 'required|string',
            ]);

            $location = Location::create($request->all());

            return response()->json([
                "status" => true,
                "data" => $location,
                "message" => "Location registered successfully.",
                "code" => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "An error occurred while registering the location.",
                "code" => 500,
            ], 500);
        }
    }

    public function updateLocation(Request $request, $_id)
    {
        try {
            $location = Location::find($_id);

            if ($location) {
                $location->update($request->all());

                return response()->json([
                    "status" => true,
                    "data" => $location,
                    "message" => "Location updated successfully.",
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
                "message" => "An error occurred while updating the location.",
                "code" => 500,
            ], 500);
        }
    }

    public function deleteLocation(Request $request, $_id)
    {
        try {
            $location = Location::find($_id);

            if ($location) {
                $location->delete();

                return response()->json([
                    "status" => true,
                    "message" => "Location deleted successfully.",
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
                "message" => "An error occurred while deleting the location.",
                "code" => 500,
            ], 500);
        }
    }

    public function searchByAddress(Request $request)
    {
        try {
            $request->validate([
                'address' => 'required|string',
            ]);

            $locations = Location::where('address', 'like', '%' . $request->address . '%')->get();

            return response()->json([
                "status" => true,
                "data" => $locations,
                "code" => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "An error occurred while searching for locations.",
                "code" => 500,
            ], 500);
        }
    }
}

