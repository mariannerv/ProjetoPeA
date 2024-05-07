public function fetchLocationAddress(Request $request)
{
    try {
        $request->validate([
            'locationId' => 'required|string',
        ]);

        // Convert the locationId to MongoDB ObjectId
        $locationId = new ObjectId($request->locationId);

        // Print the converted locationId for testing
        print("Converted Location ID: " . $locationId);

        $location = Location::where('_id', $locationId)->first();

        if ($location) {
            $address = $location->rua . ', ' . $location->freguesia . ', ' . $location->municipio . ', ' . $location->distrito . ', ' . $location->codigo_postal . ', ' . $location->pais;

            return response()->json([
                "status" => true,
                "data" => $address,
                "code" => 200,
            ]);
        } else {
            // Log the locationId to see what is being received
            \Log::info('Location ID not found: ' . $request->locationId);
            
            // Print the error message for testing
            print("Location not found for ID: " . $request->locationId);

            return response()->json([
                "status" => false,
                "message" => "Location not found.",
                "code" => 404,
            ], 404);
        }
    } catch (\Exception $e) {
        // Log the exception message for debugging
        \Log::error('Exception while fetching location address: ' . $e->getMessage());
        
        // Print the error message for testing
        print("Error: " . $e->getMessage());

        return response()->json([
            "status" => false,
            "message" => "An error occurred while retrieving location information.",
            "code" => 500,
        ], 500);
    }
}
