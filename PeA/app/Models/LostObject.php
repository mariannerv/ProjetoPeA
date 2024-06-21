<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\Model;

class LostObject extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'lostObject';

        protected $fillable = [
            'ownerEmail',
            'category',
            'description',
            'date_lost',
            'brand',
            'color',
            'size',
            'address',
            'location',
            'status',
            'postalcode',
            'lostObjectId',
            'location_id',
            'image',
        ];
}
