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
use App\Http\Controllers\Emails\SendMailController;
use Omnipay\Omnipay;
class AuctionController extends Controller
{
    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientID(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env("PAYPAL_CLIENT_SECRET"));
        $this->gateway->setTestMode(true);
    }
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

            $dateRegistered = $date = date("Y-m-d H:i:s");
            $endAuctionTime = date("Y-m-d H:i:s", strtotime($date . "1 week"));

            $request->validate([
                'objectId' => 'required|string',
                'start_date' => 'date',
                'end_date' => 'date',
                'policeStationId' => 'string',
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
                'policeStationId' => $request->policeStationId,
                'bids_list' => [],
                'bidder_list' => [],
                'pay' => false
            ]);

            return redirect()->back();
        } catch (ValidationException $e) {
            return response()->json([
                "status" => false,
                "message" => "Algo correu mal ao criar o leilão.",
                "code" => "404",
            ]);
        }
    }


    public function viewAuction($id){
            try {
            $auction = Auction::where('_id', $id)->first();
            
            if ($auction) {
              
                return view('auctions.auction', ['auction' => $auction]);
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


public function editAuction(Request $request, $id){
    try {
        $auction = Auction::where('_id', $id)->first();
        
        if (!$auction) {
            throw ValidationException::withMessages([
                'auctionId' => ['Leilão não encontrado.'],
            ]);
        }

        
        if ($auction->status !== 'deactive') {
            throw ValidationException::withMessages([
                'status' => ['Não é possível editar um leilão inicializado.'],
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

     public function deleteAuction($id){
        try {
            $auctionId = $id;
            $auction = Auction::where('_id', $auctionId)->first();

            if (!$auction) {
                return response()->json([
                    "status" => false,
                    "message" => "Leilão não encontrado.",
                    "code" => "404",
                ], 404);
            }
                $auction->delete();
                return response()->json([
                    "status" => true,
                    "message" => "Leilão eliminado.",
                    "code" => "200",
                ], 200);
            
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

    public function viewAllActiveAuctions(){
        try {
            $activeAuctions = Auction::where('status', 'active')
                          ->orWhere('highestBidderId', auth()->user()->email)
                          ->orderBy('created_at', 'desc')->get();
                          
            
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

    public function viewAllAuctions(){
        try {
            $auctions = Auction::orderBy('created_at', 'desc')->get();

            return response()->json([
                "status" => true,
                "data" => $auctions,
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

    public function finalizeorStartAuction($id){
        try {
            $auction = Auction::where('_id', $id)->first();
            
            if (!$auction) {
                throw ValidationException::withMessages([
                    'auctionId' => ['Leilão não encontrado.'],
                ]);
            }

            if ($auction->status == 'active') {
                $auction->status = 'deactive';
                $auction->save();
                return redirect()->route('auction.get',['auction'=>$auction]);            }
            if ($auction->status == 'deactive') {
                $auction->status = 'active';
                $auction->save();
                return redirect()->route('auction.get',['auction'=>$auction]);
            }
            
        } catch (ValidationException $e) {
            return response()->json([
                "status" => false,
                "code" => 404,
                "message" => "Algo correu mal ao atualizar o estado do leilão.",
                "errors" => $e->errors(), 
            ], 404);
        }
    }

    public function updateAuction(Auction $id) {
        $foundObjects = foundObject::all();
        return view('objects.found-objects.edit-auction' , ['object' => $id, 'foundObjects' => $foundObjects]);
    }

    public function signUpAuctions($id, $email) {
        $auction = Auction::where('_id', $id)->first();
        $idAuction = $auction->auctionId;
        if (in_array($email, $auction->bidder_list)) {
            return view('objects.found-objects.bidding-auction' , ['auctionId'=>$idAuction]);
        }
        $bidder = $auction->bidder_list ?? [];
        $bidder[] = $email;
        $auction->bidder_list = $bidder;
        $auction->save();
        return view('objects.found-objects.watch-auctions' , ['email'=>$email]);
    }

    public function pay($id) {
        $auction = Auction::where('_id', $id)->first();
        if ($auction->pay == false) {
        try {
        $responce = $this->gateway->purchase(array(
            'amount' => $auction->highestBid,
            'currency' => env('PAYPAL_CURRENCY'),
            'returnUrl' => url('success').'?id='.$id,
            'cancelUrl' => url('error')
 
        ))->send();
        if($responce->isRedirect()) {
            $responce->redirect();
        }
    }
    catch(\Throwable $th) {
        return $th->getMessage();
    } 
}
else {
    return "<h1>Pagamento já foi efetuado</h1>";
}
}

public function success(Request $request) {
    $id = $request->input('id');
    
   
    if ($request->input('paymentId') && $request->input('PayerID')) {
        $transaction = $this->gateway->completePurchase(array(
            'payer_id' => $request->input('PayerID'),
            'transactionReference' => $request->input('paymentId')
        ));
        $responce = $transaction->send();

        if ($responce->isSuccessful()) {
          $auction = Auction::where('_id', $id)->first();
          $object = foundObject::where('objectId', $auction->objectId)->first();
          $auction->pay = true;

          $avisouser = "Informamos que o seu pagamento foi feito com sucesso". 
            "O seu leilão:<a href='http://localhost:8000/auctions/'". $auction->_id .">ver leilão</a>. " . 
            "<br> Contacte o policia responsavel: " . $object->name . 
            " por email: " . $object->email . 
            " ou número de telefone: "  . $object->number . 
            ". Se tiver algum problema, contacte o administrador projetopea1@gmail.com"; 
          ;

          app(SendMailController::class)->sendWelcomeEmail(
            $auction->highestBidderId, 
            $avisouser,
            "Pagamento efetuado"  // subject
        );

        $avisopolice = "Imformamos que o pagamento sobre o leilão foi efetuada" .
        "<br> contacte o utilizador " . $auction->highestBidderId .
        "O seu leilão:<a href='http://localhost:8000/auctions/'". $auction->_id .">ver leilão</a>. ";

        }
        app(SendMailController::class)->sendWelcomeEmail(
            $auction->highestBidderId,
            $avisopolice,
            "Pagamendo de leilão efetuado"  // subject
        );
        $auction->save();
        return "<h1>Pagamento efetuado</h1>";

    }

    }

    public function finishauction($id) {
        $auction = Auction::where('_id', $id)->first();
        $aviso = "Parabens voce ganhou o leilão <br>" .
        "Pode já pagar o seu objeto: <a href='http://localhost:8000/pay/".$id."'>aqui</a>.";
        
        app(SendMailController::class)->sendWelcomeEmail(
            $auction->highestBidderId,
            $aviso,
            "Ganhou o leilão"  // subject
        );
        $auction->status = "deactive";
        $auction->save();

    }
  

}

 

