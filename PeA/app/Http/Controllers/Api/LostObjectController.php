<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\DB;
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
use App\Models\lostObject;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Emails\crossCheckMailController;
use App\Http\Controllers\Emails\SendMailController;
use Exception;
use Illuminate\Support\Facades\Validator;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Http;
use App\Models\Categoria;

class lostObjectController extends Controller
{
    protected $locationController;

    public function registerlostObject(Request $request)
{
    $locationController = new LocationController();
    $ownerEmail = $request->input('ownerEmail');
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
        ]);

        if ($val->fails()) {
            return redirect()->back()->withErrors($val)->withInput();
        }

        $lostObject = LostObject::create([
            "ownerEmail" => $request->input('ownerEmail'),
            "description" => $request->input('description'),
            "date_lost" => $request->input('date_lost'),
            "brand" => $request->input('brand'),
            "color" => $request->input('color'),
            "size" => $request->input('size'),
            "category" => $request->input('category'),
            "locsign" => $request->input('uuid'),
            "status" => "Lost",
        ]);

        $locationData = [
            "locsign" => $request->input('uuid'),
            "coordenadas" => [],
            "morada" => $request->input('address') ?? $request->input('map-address'),
            "localidade" => $request->input('city') ?? $request->input('map-city'),
            "codigo_postal" => $request->input('postalcode') ?? $request->input('map-postalcode')
        ];

        if ($request->has('latitude') && $request->has('longitude')) {
            $locationData['coordenadas'] = [$request->input('latitude'), $request->input('longitude')];
        }
        $request->merge($locationData);
        $location = $locationController->registerLocation($request);

        return response()->json([
            'message' => 'Lost object registered successfully',
            'lost_object' => $lostObject,
            'location' => $location
        ]);
    } catch (Exception $e) {
        $exceptionInfo = [
            'message' => $e->getMessage(),
        ];
        return response()->json([
            "status" => false,
            "message" => "Ocorreu um erro ao registrar o objeto perdido.",
            "exception" => $exceptionInfo,
            "code" => 500,
        ], 500);
    }
}




public function getObjects($foundObjectId, $lostObjectId)
{
    // Buscar o objeto encontrado pelo ID
    $foundObject = FoundObject::find($foundObjectId);

    // Buscar o objeto perdido pelo ID
    $lostObject = lostObject::find($lostObjectId);

    // Verificar se ambos os objetos foram encontrados
    if (!$foundObject || !$lostObject) {
        return redirect()->back()->withErrors('One or both objects not found.');
    }
    $matchPercentage = $this->calculateMatchPercentage($foundObject, $lostObject);
    // Passar os objetos para a view
    return view('objects.found-objects.compare',['foundObjects' => $foundObject , 'lostObjects' => $lostObject , 'compare' => $matchPercentage]);
}

public function add(FoundObject $foundObject, lostObject $lostObject) {
    $matchPercentage = $this->calculateMatchPercentage($foundObject, $lostObject);

    $possibleOwner = ['owner' => $lostObject->ownerEmail, 'match' => $matchPercentage , 'lostObjectid' => $lostObject->_id];

    $fObject = FoundObject::where('_id', $foundObject->_id)->first();

    if ($fObject) {
        // Clone a propriedade possible_owner para garantir que a modificação indireta funcione
        $owners = $fObject->possible_owner ?? [];
        
        // Adiciona o novo possível proprietário ao array
        $owners[] = $possibleOwner;
        
        // Reatribui o array modificado de volta à propriedade possible_owner
        $fObject->possible_owner = $owners;

        // Salva as mudanças no banco de dados
        $fObject->save();
        return redirect()->route('found-object.get' , $foundObject->_id);
    }
}

public function ownerbject($foundObjectId) {
    $object = FoundObject::find($foundObjectId);
    return view('objects.found-objects.owner-object',['object' => $object]);
}

public function notifyOwner(FoundObject $foundObject, $lostObjectid, $email) {
    try {
        $lostObject = lostObject::find($lostObjectid);
        
        $aviso = "Informamos que o seu objeto com a seguinte descrição: " . $lostObject->description . 
                 " da marca " . $lostObject->brand . ". " . 
                 "Veja o seu objeto <a href='http://localhost:8000/lost-objects/".$lostObject->id."'>aqui</a>. " .
                 "Possa ter sido achado. Para mais informações, contacte o policial " . $foundObject->name . 
                 " por email: " . $foundObject->email . 
                 " ou número de telefone: "  . $foundObject->number . 
                 ". Se tiver algum problema, contacte o administrador projetopea1@gmail.com";
        
        // Envia o email
        app(SendMailController::class)->sendWelcomeEmail(
            "fc56948@alunos.fc.ul.pt", // toEmail
            $aviso,
            "Objeto Perdido possivelmente encontrado"  // subject
        );

        // Remover o possível dono da lista de possíveis donos
        $foundObject = FoundObject::find($foundObject->_id);

        if ($foundObject) {
            // Filtra o array possible_owner para remover o item com o lostObjectid fornecido
            $newPossibleOwners = array_filter($foundObject->possible_owner, function ($owner) use ($lostObjectid) {
                return $owner['lostObjectid'] !== $lostObjectid;
            });

            // Atualiza o documento com o novo array possible_owner
            $foundObject->possible_owner = array_values($newPossibleOwners); // array_values para reindexar o array
            $foundObject->save();
        }

        // Redireciona de volta com uma mensagem de sucesso
        return redirect()->back()->with('success', 'Utilizador: '. $email .  ' notificado com sucesso!') ;
    } catch (\Exception $e) {
        // Redireciona de volta com uma mensagem de erro
        return redirect()->back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
}


public function getAlllostObjects()
{
    try {
        Log::info('Fetching all lost objects.'); // Logging the fetch operation

        $lostObjects = lostObject::all();
        
        return response()->json([
            "status" => true,
            "data" => $lostObjects,
            "code" => 200,
        ]);
    } catch (\Exception $e) {
        Log::error('Error fetching all lost objects: ' . $e->getMessage()); // Logging the error
        
        return response()->json([
            "status" => false,
            "message" => "An error occurred while fetching all lost objects.",
            "code" => 500,
        ], 500);
    }
}


    public function getlostObject(String $id){
        try {
            $object = lostObject::where('_id', $id)->first();

            if ($object) {
                return view('objects.lost-objects.lost-object', ['object' => $object]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Objeto não encontrado.",
                    "code" => 404,
                ], 404);
            }
        } catch (Exception $e) {
            $exceptionInfo = [
                'message' => $e->getMessage(),
                
                // Add more properties as needed
            ];
            return response()->json([
                "status" => false,
                "message" => "Ocorreu um erro ao recuperar as informações do objeto.",
                "exception" => $exceptionInfo,
                "code" => 500,
            ], 500);
        }
    }
    

    public function getStatistics()
    {
        try {
            // Group lost objects by category and date found
            $statistics = DB::table('lostObject')
                ->select('categoryId', DB::raw('count(*) as count'))
                ->groupBy('categoryId')
                ->get();

            $data = [
                'categories' => $statistics->pluck('categoryId'),
                'counts' => $statistics->pluck('count')
            ];

            return response()->json([
                "status" => true,
                "data" => $data,
                "code" => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "An error occurred while fetching statistics for lost objects.",
                "code" => 500,
            ], 500);
        }
    }
    public function updatelostObject(Request $request){
        $object = lostObject::where('lostObjectId', $request->lostObjectId)->first();

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


    public function deletelostObject(Request $request){
    try {
        $lostObjectId = $request->lostObjectId;
        
        $lostObject = lostObject::where('_id', $lostObjectId)->first();

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

    $lostObject = lostObject::where('lostObjectId', $lostObjectId)->first();

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

            app(crossCheckMailController::class)->sendCrossCheckEmail(
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

        $objects = lostObject::where('description', 'like', '%' . $request->description . '%')->get();

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

public function editlostObject($objectId) {
    $lostObject = lostObject::findOrFail($objectId);
    if ($lostObject){
        return view('objects.lost-objects.partials.lost-object-editform' , ['lostObject' => $lostObject]);
    } else {
        return response()->json([
            "status" => false,
            "message" => "Objeto não encontrado.",
            "code" => "404",
        ], 404);
    }
}

}