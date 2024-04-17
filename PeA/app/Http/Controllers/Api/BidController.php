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
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\bidMailUpdateController;

class BidController extends Controller
{
public function placeBid(Request $request)
{
    $auctionId = $request->auctionId;
    $auction = Auction::where('auctionId', $auctionId)->first();
    $currentHightestBidder = null;

    if (!$auction) {
        return response()->json([
            "status" => false,
            "message" => "Leilão não encontrado.",
            "code" => 404,
        ]);
    }

    if ($auction->status === 'finished') {
        return response()->json([
            "status" => false,
            "message" => "Este leilão já terminou.",
            "code" => 403,
        ]);
    }

    $currentHighestBidderEmail = $auction->highestBidderId;
    $request->validate([
        'amount' => 'required|numeric',
        'bidderId' => [
            'required',
            'string',
            Rule::exists('users', 'email'),
        ],
        'auctionId' => 'required|string',
    ]);

    $newBidAmount = $request->amount;
    if ($auction->highestBid >= $newBidAmount) {
        return response()->json([
            "status" => false,
            "message" => "O valor da licitação deve ser superior ao valor mais alto atual.",
            "code" => 422, 
        ]);
    }


    $timestamp = Carbon::now()->timestamp;
    $uuid = (string) Str::uuid();
    $bid = Bid::create([
        "bidId" => $uuid,
        "amount" => $request->amount,
        "bidderId" => $request->bidderId,
        "bidDate" => $timestamp,
        "auctionId" => $request->auctionId,
    ]);
    $user = User::where('email', $request->bidderId)->first();
        if ($user) {
            $user->push('bid_history', $bid->bidId);
            $user->save();
        } else {
            return response()->json([
                "status" => false,
                "code" => 404,
                "message" => "Utilizador não encontrado.",
            ], 404);
        }

    if ($bid) {
        $now = Carbon::now();
        
        $bidDate = new UTCDateTime(now()->timestamp * 1000);;
        $auction->highestBid = $request->amount;
        $auction->highestBidderId = $request->bidderId;
        $auction->push('bids_list', $bid->bidId);
        $auction->recentBidDate = $bidDate;
        $auction->save();


        $emailContent = "A sua licitação foi ultrapassada:\n";
        $emailContent .= "Licitação mais alta: " . $request->amount . "\n";
        $emailContent .= "ID do leilão: " . $auction->auctionId . "\n";
        $emailContent .= "Data de fim: " . $auction->end_date . "\n";

        $sendMailController = new bidMailUpdateController();
        $sendMailController->sendBidEmail(
            $currentHighestBidderEmail,
            "Licitação ultrapassada",
            "Novo valor mais alto: " . $request->amount . "€",
            "ID do leilão: " . $auction->auctionId,
            "Data de fim: " . $auction->end_date,
        );
    }

    return response()->json([
        "status" => true,
        "message" => "Licitação lançada com sucesso.",
        "code" => 200, 
    ]);
}

}
