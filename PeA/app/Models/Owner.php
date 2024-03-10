<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model as Eloquent;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;

class Owner extends Eloquent implements AuthenticatableContract
{
    use HasFactory, Authenticatable,HasApiTokens, Notifiable;

    protected $connection = 'mongodb';

    // equivalent to mySQL table    

    protected $collection = 'Owner';
    protected $primaryKey = '_id'; 

    protected $fillable = ['name', 
                            'gender', 
                            'birthdate', 
                            'addressId', 
                            'civilId', 
                            'taxId', 
                            'contactNumber', 
                            'email', 
                            "remember_token", 
                            'password'];

    protected $hidden = [
        'remember_token',
        'password'
    ];
} 