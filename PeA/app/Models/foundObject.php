<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\Model;

class foundObject extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $connection = 'mongodb';
    protected $collection = 'foundObject';

        protected $fillable = [
        'objectId',
        'possible_owner',
        'category',
        'description',
        'location',
        'location_coords',
        'value',
        'date_found',
        'date_registered',
        'deadlineForAuction',
    ];

    protected $casts = [
        'objectId' => 'string', // Cast the objectId field to a string
    ];

}
