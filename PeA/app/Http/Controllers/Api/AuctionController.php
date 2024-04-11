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
use Illuminate\Validation\ValidationException;

class AuctionController extends Controller
{
    public function createAuction(Request $request){
        try {
            $objectId = $request->objectId;

          
            $exists = foundObject::where('objectId', $objectId)->exists(); //pra ver se o objeto existe
            $existsInAuction = Auction::where('objectId', $objectId)->exists(); //pra ver se já existe algum leilão com este objeto

            if (!$exists) {
                
                throw ValidationException::withMessages([
                    'objectId' => ['The specified objectId does not exist.'],
                ]);
            }

            if ($existsInAuction) {
                throw ValidationException::withMessages([
                    'objectId' => ['Objeto já associado a um leilão.'],
                ]);
            }

            $dateRegistered = new UTCDateTime(now()->timestamp * 1000);
            $endAuctionTime = new UTCDateTime(now()->addWeek()->timestamp * 1000);

            $request->validate([
                'objectId' => 'required|string',
                'start_date' => 'date',
                'end_date' => 'date',
            ]);

           
            $uuid = (string) Str::uuid();

            Auction::create([
                "auctionId" => $uuid,
                "highestBid" => 0,
                'highestBidderId' => "",
                'recentBidDate' => null,
                'start_date' => $dateRegistered,
                'end_date' => $endAuctionTime,
                'objectId' =>$request->objectId,
                'status' => 'active',
                'bids_list' => []
            ]);

            return response()->json([
                "status" => true,
                "message" => "Leilão criado com sucesso",
                "code" => "200",
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                "status" => false,
                "message" => "Algo correu mal ao criar o leilão.",
                "code" => "404",
            ]);
        }
    }


    public function viewAuction(Request $request){
            try {
                $request->validate([
                    'auctionId' => 'required|string',
            ]);

            $auction = Auction::where('auctionId', $request->auctionId)->first();

            if ($auction) {
              
                return response()->json([
                    "status" => true,
                    "data" => $auction,
                    "code" => 200,
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Leilão não encontrado.",
                    "code" => 404,
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Ocorreu um erro ao recuperar as informações do leilão.",
                "code" => 500,
            ], 500);
        }
    }


public function editAuction(Request $request){
    try {
        $auction = Auction::where('auctionId', $request->auctionId)->first();
        
        if (!$auction) {
            throw ValidationException::withMessages([
                'auctionId' => ['Leilão não encontrado.'],
            ]);
        }

        
        if ($auction->status !== 'active') {
            throw ValidationException::withMessages([
                'status' => ['Não é possível editar um leilão finalizado.'],
            ]);
        }

        $request->validate([
            'end_date' => 'date',
            'object_id' => 'string',
        ]);

      
        $auction->update($request->all());

        return response()->json([
            "status" => true,
            "code" => 200,
            "message" => "Leilão atualizado com sucesso.",
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            "status" => false,
            "code" => 404,
            "message" => "Algo correu mal ao atualizar o leilão.",
            "errors" => $e->errors(), 
        ], 404);
    }
}

     public function deleteAuction(Request $request){
        try {
            $auctionId = $request->auctionId;
            $auction = Auction::where('auctionId', $auctionId)->first();

        if ($auction) {
            $auction->delete();
            return response()->json([
                "status" => true,
                "message" => "Leilão apagado com sucesso.",
                "code" => "200",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Leilão não encontrado.",
                "code" => "404",
            ], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            "status" => false,
            "message" => "Oops! Algo correu mal ao tentar apagar o leilão.",
            "code" => "500",
        ], 500);
    }
    }

  public function bidHistory(Request $request){
  
    $request->validate([
        'auctionId' => 'required|string',
    ]);

    
    $auction = Auction::where('auctionId', $request->auctionId)->first();

    if ($auction) {
       
        $bids = Bid::where('auctionId', $auction->auctionId)->get();

        return response()->json([
            "status" => true,
            "bids" => $bids,
        ]);
    } else {
        return response()->json([
            "status" => false,
            "message" => "Leilão não encontrado.",
        ], 404);
    }
}

public function viewAllAuctions(){
    try {
        $activeAuctions = Auction::where('status', 'active')->get();

        return response()->json([
            "status" => true,
            "data" => $activeAuctions,
            "code" => 200,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            "status" => false,
            "message" => "Ocorreu um erro ao recuperar as informações dos leilões ativos.",
            "code" => 500,
        ], 500);
    }
}
 }

