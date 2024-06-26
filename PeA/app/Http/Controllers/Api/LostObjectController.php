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
use App\Models\LostObject;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Emails\crossCheckMailController;
use App\Http\Controllers\Emails\SendMailController;
use Exception;
use Illuminate\Support\Facades\Validator;
use MongoDB\BSON\ObjectId;


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
            $val = Validator::make($request->all(),[
                'ownerEmail' => 'required|email',
                'category' => 'required|string',
                'description' => 'required|string',
                'date_lost' => 'required|date',
                'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                // 'location_id' => 'required|string',
            ]);
    
            if ($val->fails()){
                return redirect()->back()->withErrors($val)->withInput();
            }
    
            $uuid = (string) Str::uuid();
            
            // Process image upload, if present
            $imageName = null;
            if ($request->hasFile('img')) {
                $imageName = time().'_'.uniqid().'.'.$request->img->extension();
                $request->img->move(public_path('images/lost-objects-img'), $imageName);
            }
    
            // Create the lost object with provided data
            $lostObject = LostObject::create([
                "ownerEmail" => $ownerEmail,
                "description" => $request->input('description'),
                "date_lost" => $request->input('date_lost'),
                "brand" => $request->input('brand'),
                "color" => $request->input('color'),
                "size" => $request->input('size'),
                "category" => $request->input('category'),
                "address" => $request->input('address'),
                "location" => $request->input('location'),
                "postalcode" => $request->input('postalcode'),
                "location_id" => $uuid,
                "status" => "Lost",
                "lostObjectId" => 0,
                "image" => $imageName,
            ]);
    
            event(new Registered($lostObject));
    
            return response()->json([
                'message' => 'Lost object registered successfully',
                'lost_object' => $lostObject,
            ]);
    
        } catch (Exception $e) {
            // Return exception details for debugging
            return response()->json([
                "status" => false,
                "message" => "Ocorreu um erro ao registrar o objeto perdido.",
                "exception_message" => $e->getMessage(),
                "code" => 500,
            ], 500);
        }
    }
    
public function getAllObjects()
{
$foundObjects = FoundObject::all();
$lostObjects = LostObject::all();
return view('objects.found-objects.all-objects' ,['foundObjects' => $foundObjects , 'lostObjects' => $lostObjects]);
}

public function getObjects($foundObjectId, $lostObjectId)
{
    // Buscar o objeto encontrado pelo ID
    $foundObject = FoundObject::find($foundObjectId);

    // Buscar o objeto perdido pelo ID
    $lostObject = LostObject::find($lostObjectId);

    // Verificar se ambos os objetos foram encontrados
    if (!$foundObject || !$lostObject) {
        return redirect()->back()->withErrors('One or both objects not found.');
    }
    $matchPercentage = $this->calculateMatchPercentage($foundObject, $lostObject);
    // Passar os objetos para a view
    return view('objects.found-objects.compare',['foundObjects' => $foundObject , 'lostObjects' => $lostObject , 'compare' => $matchPercentage]);
}

public function add(FoundObject $foundObject, LostObject $lostObject) {
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

public function adduser(FoundObject $foundObject, LostObject $lostObject) {
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
        app(SendMailController::class)->sendWelcomeEmail(
            $fObject->email, // toEmail
            "Novo possivel dono foi adicionado",
            "Novo possivel dono"  // subject
        );

        return redirect()->back()->with('success', 'policia notificado com sucesso');
    }
}
public function removeOwner(FoundObject $foundObject, $lostObjectid) {
    $foundObject = FoundObject::find($foundObject->_id);
    $lostObject = LostObject::find($lostObjectid);
    $lostObjectid = $lostObject->_id;
    if ($foundObject) {
        $newPossibleOwners = array_filter($foundObject->possible_owner, function ($owner) use ($lostObjectid) {
            return $owner['lostObjectid'] !== $lostObjectid;
        });
        $foundObject->possible_owner = array_values($newPossibleOwners); 
        $foundObject->save();
        return redirect()->back()->with('success', 'Possivel utilizador removido');
    } 

}

public function ownerbject($foundObjectId) {
    $object = FoundObject::find($foundObjectId);
    return view('objects.found-objects.owner-object',['object' => $object]);
}

public function notifyOwner(FoundObject $foundObject, $lostObjectid, $email) {
    try {
        $lostObject = LostObject::find($lostObjectid);
        
        $aviso = "Informamos que o seu objeto com a seguinte descrição: " . $lostObject->description . 
                 " da marca " . $lostObject->brand . ". " . 
                 "Veja o seu objeto <a href='http://localhost:8000/lost-objects/".$lostObject->id."'>aqui</a>. " .
                 "Possa ter sido achado. Para mais informações, contacte o policial " . $foundObject->name . 
                 " por email: " . $foundObject->email . 
                 " ou número de telefone: "  . $foundObject->number . 
                 ". Se tiver algum problema, contacte o administrador projetopea1@gmail.com";
        
        // Envia o email
        app(SendMailController::class)->sendWelcomeEmail(
            $email, // toEmail
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


    public function getAllLostObjects()
{
    try {
        $lostObjects = LostObject::orderBy('created_at', 'desc')->get();
        
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

    public function getLostObject(String $id){
        try {
            $object = LostObject::where('_id', $id)->first();

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
            $statistics = DB::table('LostObject')
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
    public function updateLostObject(Request $request, $id)
    {
        $object = LostObject::where('_id', $id)->first();
    
        if ($object) {
            $request->validate([
                'ownerEmail' => 'email|exists:users,email',
                'category' => 'string'  ,
                'brand' => 'string',
                'color' => 'string',
                'size' => 'string',
                'description' => 'string',
                'date_lost' => 'date',
                'location' => 'string',
                'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
           
            if(isset($request->img)) {
                if ($request->hasFile('img')) {
                    $imageName = time().'_'.uniqid().'.'.$request->img->extension();
                    $request->img->move(public_path('images/lost-objects-img'), $imageName);
        
                   
                }
        
                $object->update([
                    "description" => $request->input('description'),
                    "date_lost" => $request->input('date_lost'),
                    "brand" => $request->input('brand'),
                    "color" => $request->input('color'),
                    "size" => $request->input('size'),
                    "category" => $request->input('category'),
                    "address" => $request->input('address'),
                    "location" => $request->input('location'),
                    "postalcode" => $request->input('postalcode'),
                    "status" => "Lost",
                    "lostObjectId" => 0,
                    "image" => $imageName,
                ]
                );
            }
            else {
                $object->update([
                    "description" => $request->input('description'),
                    "date_lost" => $request->input('date_lost'),
                    "brand" => $request->input('brand'),
                    "color" => $request->input('color'),
                    "size" => $request->input('size'),
                    "category" => $request->input('category'),
                    "address" => $request->input('address'),
                    "location" => $request->input('location'),
                    "postalcode" => $request->input('postalcode'),
                    "status" => "Lost",
                    "lostObjectId" => 0,
                  
                ]
                );
            }
         
    
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
            $possibleOwner[]     = $lostObject->ownerEmail;
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

public function editLostObject($objectId) {
    $lostObject = LostObject::findOrFail($objectId);
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