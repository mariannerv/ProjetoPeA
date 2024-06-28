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
use App\Http\Controllers\Emails\bidMailUpdateController;

class BidController extends Controller
{
public function placeBid(Request $request)
{
    $auctionId = $request->auctionId;
    $auction = Auction::where('auctionId', $auctionId)->first();

    if (!$auction) {
        return redirect()->back()->withErrors(['Leilão' => 'Leilão não existe.']);
    }

    if ($auction->status === 'deactive') {
        return redirect()->back()->withErrors(['Leilão' => 'Leilão já acabou.']);

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
        return redirect()->back()->withErrors(['Leilão' => 'O valor da licitação deve ser superior ao valor mais alto atual.']);
    }


    $timestamp = date("Y-m-d H:i:s");
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
            return redirect()->back()->withErrors(['Utilizador' => 'Utilizador não existe']);

        }
        $bidDate = date("Y-m-d H:i:s");
        $auction->highestBid = $request->amount;
        $auction->highestBidderId = $request->bidderId;
        $auction->push('bids_list', $bid->bidId);
        $auction->recentBidDate = $bidDate;
        $auction->save();
    if ($bid && $auction->highestBidderId != null) {
        
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
        return redirect()->route('auction.get', ['auction'=>$auction->_id]);
    }


}

}
