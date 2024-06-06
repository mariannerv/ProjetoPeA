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
        'brand',
        'color',
        'size',
        'description',
        'location',
        'name',
        'number',
        'email',
        'location_id',
        'location_coords',
        'value',
        'date_found',
        'date_registered',
        'deadlineForAuction',
        'estacao_policia',

    ];

    protected $casts = [
        'objectId' => 'string',
        'date_registered' => 'datetime',
        'deadlineForAuction' => 'datetime',
    ];

}