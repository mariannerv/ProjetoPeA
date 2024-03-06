<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    // equivalent to mySQL table    
    protected $collection = 'Owner';


} 