<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'auction_user');
    }
}
