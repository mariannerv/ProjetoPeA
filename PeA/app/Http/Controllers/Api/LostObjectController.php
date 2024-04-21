<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\foundObject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\LostObject;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class LostObjectController extends Controller
{
    public function registerLostObject(Request $request){


            $ownerEmail = $request->ownerEmail;
            $owner = User::where('email', $ownerEmail)->first();

            if (!$owner){
                return response()->json([
                "status" => false,
                "message" => "Utilizador não encontrado.",
                "code" => 404,
            ]);
            }

            $request->validate([
                'ownerEmail' => 'required|email',
                'category' => 'required|string',
                'brand' => 'nullable|string',
                'color' => 'nullable|string',
                'size' => 'nullable|string',
                'description' => 'required|string',
                'date_lost' => 'required|date',
                'location' => 'required|string',
            ]);

            $uuid = (string) Str::uuid();

            $lostObject = LostObject::create([

                "ownerEmail" => $request->ownerEmail, 
                "category" => $request->category,
                'brand' => $request->brand,
                'color' => $request->color,
                'size' => $request->size,
                "description" => $request->description,
                "date_lost" => $request->date_lost,
                "location" => $request->location,
                "status" => 'Lost',
                'lostObjectId' => $uuid
            ]);
            

            if ($owner) {
                $owner->push('lost_objects', $lostObject->lostObjectId);
                $owner->save();
            } else {
                return response()->json([
                    "status" => false,
                    "code" => 404,
                    "message" => "Utilizador não encontrado.",
                ], 404);
            }

            return response()->json([
                    "status" => true,
                    "code" => 200,
                    "message" => "Objeto perdido registado com sucesso.",
                ], 200);

    }

    public function getLostObject(Request $request){
        try {
            $request->validate([
                'lostObjectId' => 'required|string',
            ]);

            $object = LostObject::where('lostObjectId', $request->lostObjectId)->first();

            if ($object) {
              
                return response()->json([
                    "status" => true,
                    "data" => $object,
                    "code" => 200,
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Objeto não encontrado.",
                    "code" => 404,
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Ocorreu um erro ao recuperar as informações do objeto.",
                "code" => 500,
            ], 500);
        }
    }


    public function updateLostObject(Request $request){
        $object = LostObject::where('lostObjectId', $request->lostObjectId)->first();

        if ($object) {
            $request->validate([
                'ownerEmail' => 'email|exists:users,email',
                'category' => 'string',
                'brand' => 'string',
                'color' => 'string',
                'size' => 'string',
                'description' => 'string',
                'date_lost' => 'date',
                'location' => 'string',
            
        ]);

        $object->update($request->all());

        return response()->json([
            "status" => true,
            "code" => 200,
            "message" => "Objeto atualizado com sucesso.",
        ]);
    } else {
        return response()->json([
            "status" => false,
            "code" => 404,
            "message" => "Objeto não encontrado.",
        ], 404);
    }
    }


    public function deleteLostObject(Request $request){
    try {
        $lostObjectId = $request->lostObjectId;

        $lostObject = LostObject::where('lostObjectId', $lostObjectId)->first();

        if (!$lostObject) {
            return response()->json([
                "status" => false,
                "message" => "Objeto não encontrado.",
                "code" => "404",
            ], 404);
        }

        $user = User::where('email', $lostObject->ownerEmail)->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "Dono do objeto não encontrado.",
                "code" => "404",
            ], 404);
        }

        $user->lost_objects = array_diff($user->lost_objects, [$lostObjectId]);
        $user->save();

        // Delete the lost object
        $lostObject->delete();

        return response()->json([
            "status" => true,
            "message" => "Objeto apagado com sucesso.",
            "code" => "200",
        ]);

    } catch (\Exception $e) {
        return response()->json([
            "status" => false,
            "message" => "Oops! Algo correu mal ao tentar apagar o objeto.",
            "code" => "500",
        ], 500);
    }
}

public function crossCheck(Request $request)
{
    $threshold = 0.5; 

    $lostObjectId = $request->lostObjectId;

    $lostObject = LostObject::where('lostObjectId', $lostObjectId)->first();

    Log::info('Lost object attributes: ' . json_encode($lostObject));

    $foundObjects = FoundObject::all();
    $matches = [];
    $foundObjectsCount = count($foundObjects);

    Log::info('Found object count: ' . json_encode($foundObjectsCount));
    
    foreach ($foundObjects as $foundObject) {
        $matchPercentage = $this->calculateMatchPercentage($foundObject, $lostObject);

        Log::info('Match percentage for found object ' . $foundObject->id . ': ' . $matchPercentage);

        // Include found object information along with match percentage
        $matches[] = [
            'found_object_id' => $foundObject->id,
            'found_object' => $foundObject, // Include the found object data
            'match_percentage' => $matchPercentage,
        ];

        if ($matchPercentage >= 70) {
            $possibleOwner = $foundObject->possible_owner ?? [];
            $possibleOwner[] = $lostObject->ownerEmail;
            $foundObject->possible_owner = $possibleOwner;
            $foundObject->save();
        }
    }

    // Check if matches array is empty
    if (empty($matches)) {
        return response()->json([
            'message' => 'No matches found.',
        ]);
    }

    // Return JSON response with matches
    return response()->json([
        'matches' => $matches,
    ]);
}







private function descriptionMatch($foundDescription, $lostDescription)
{
    $foundDescription = strtolower($foundDescription);
    $lostDescription = strtolower($lostDescription);

    $foundWords = explode(' ', $foundDescription);
    $lostWords = explode(' ', $lostDescription);

    $commonWords = array_intersect($foundWords, $lostWords);

   
    $matchPercentage = count($commonWords) / max(count($foundWords), count($lostWords));
    $threshold = 0.5; 
    return $matchPercentage >= $threshold;
}


private function calculateMatchPercentage($foundObject, $lostObject)
{
    // Total number of attributes being compared, including the description
    $totalAttributes = 6;

    // Weight for each attribute (excluding the description)
    $attributeWeight = (100 - 10) / ($totalAttributes - 1);

    // Calculate the match percentage for the description
    $descriptionMatchPercentage = $this->descriptionMatch($foundObject->description, $lostObject->description) ? 10 : 0;

    // Initialize the total match percentage with the description match percentage
    $matchPercentage = $descriptionMatchPercentage;

    // Add match percentage for other attributes
    if ($foundObject->location === $lostObject->location) {
        $matchPercentage += $attributeWeight; 
    }

    if ($foundObject->category === $lostObject->category) {
        $matchPercentage += $attributeWeight; 
    }
    if ($foundObject->color === $lostObject->color) {
        $matchPercentage += $attributeWeight; 
    }
    if ($foundObject->brand === $lostObject->brand) {
        $matchPercentage += $attributeWeight; 
    }
    if ($foundObject->size === $lostObject->size) {
        $matchPercentage += $attributeWeight; 
    }

    return $matchPercentage;
}


}