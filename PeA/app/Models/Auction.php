<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\Model;

class Auction extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'auction';

    protected $fillable = [
        'auctionId',
        'highestBid',
        'highestBidderId',
        'recentBidDate',
        'start_date',
        'end_date',
        'objectId',      
        'status',
        'bids_list',
        'policeStationId',
        'bidder_list',
        'pay',
    ];


}
