<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoundObject;
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
use App\Http\Controllers\Emails\CrossCheckMailController;
use App\Http\Controllers\Emails\SendMailController;
use Exception;
use Illuminate\Support\Facades\Validator;

class LostObjectController extends Controller
{
    public function registerLostObject(Request $request)
    {
        $ownerEmail = $request->ownerEmail;
        $owner = User::where('email', $ownerEmail)->first();

        if (!$owner) {
            return response()->json([
                "status" => false,
                "message" => "Utilizador não encontrado.",
                "code" => 404,
            ]);
        }
        try {
            $val = Validator::make($request->all(), [
                'ownerEmail' => 'required|email',
                'category' => 'required|string',
                'description' => 'required|string',
                'date_lost' => 'required|date',
                // 'location_id' => 'required|string',
            ]);
            if ($val->fails()) {
                return redirect()->back()->withErrors($val)->withInput();
            }

            $lostObject = LostObject::create([
                "ownerEmail" => $ownerEmail,
                "description" => $request->input('description'),
                "date_lost" => $request->input('date_lost'),
                "brand" => $request->input('brand'),
                "color" => $request->input('color'),
                "size" => $request->input('size'),
                "category" => $request->input('category'),
                "location" => "12345fsaw1",
                "status" => "Lost",
                "lostObjectId" => 0,
            ]);

            event(new Registered($lostObject));

            // app(SendMailController::class)->sendWelcomeEmail(
            //     $request->input('email'),
            //     "Objecto registado com sucesso!",
            //     "Obrigado por ter registado um objeto, esperamos que o consiga encontrar."
            // );

            return response()->json([
                'message' => 'Lost object registered successfully',
                'lost_object' => $lostObject,
            ]);
        } catch (Exception $e) {
            $exceptionInfo = [
                'message' => $e->getMessage(),
            ];
            return response()->json([
                "status" => false,
                "message" => "Ocorreu um erro ao recuperar as informações do objeto.",
                "exception" => $exceptionInfo,
                "code" => 500,
            ], 500);
        }
    }

    public function getAllLostObjects()
    {
        try {
            $lostObjects = LostObject::all();

            return response()->json([
                "status" => true,
                "data" => $lostObjects,
                "code" => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "An error occurred while fetching all lost objects.",
                "code" => 500,
            ], 500);
        }
    }

    public function getLostObject(String $id)
    {
        try {
            $object = LostObject::where('_id', $id)->first();

            if ($object) {
                return view('objects.lost-object', ['object' => $object]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Objeto não encontrado.",
                    "code" => 404,
                ], 404);
            }
        } catch (\Exception $e) {
            $exceptionInfo = [
                'message' => $e->getMessage(),
            ];
            return response()->json([
                "status" => false,
                "message" => "Ocorreu um erro ao recuperar as informações do objeto.",
                "exception" => $exceptionInfo,
                "code" => 500,
            ], 500);
        }
    }

    public function updateLostObject(Request $request)
    {
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

    public function deleteLostObject(Request $request)
    {
        try {
            $lostObjectId = $request->lostObjectId;

            $lostObject = LostObject::where('_id', $lostObjectId)->first();

            if (!$lostObject) {
                return response()->json([
                    "status" => false,
                    "message" => "Objeto não encontrado.",
                    "object id" => $lostObjectId,
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

            $matches[] = [
                'found_object_id' => $foundObject->id,
                'found_object' => $foundObject,
                'match_percentage' => $matchPercentage,
            ];

            if ($matchPercentage >= 70) {
                $possibleOwner = $foundObject->possible_owner ?? [];
                $possibleOwner[] = $lostObject->ownerEmail;
                $foundObject->possible_owner = $possibleOwner;
                $foundObject->save();

                app(CrossCheckMailController::class)->sendCrossCheckEmail(
                    $possibleOwner, // toEmail
                    "Objeto Perdido possivelmente encontrado",  // subject
                    "Um objeto perdido que registrou teve um match de 70% ou mais com um dos nossos objetos achados, para mais informações siga o link abaixo."
                );
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

    public function searchByDescription(Request $request)
    {
        try {
            $request->validate([
                'description' => 'required|string',
            ]);

            $objects = LostObject::where('description', 'like', '%' . $request->description . '%')->get();

            return response()->json([
                "status" => true,
                "data" => $objects,
                "code" => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "An error occurred while searching for lost objects.",
                "code" => 500,
            ], 500);
        }
    }

    private function calculateMatchPercentage($foundObject, $lostObject)
    {
        $totalAttributes = 6;

        $attributeWeight = (100 - 10) / ($totalAttributes - 1);

        $descriptionMatchPercentage = $this->descriptionMatch($foundObject->description, $lostObject->description) ? 10 : 0;

        $matchPercentage = $descriptionMatchPercentage;

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

    public function editLostObject(LostObject $lostObject)
    {
        return view('profile.users.partials.usereditform', ['user' => $lostObject]);
    }
}

