<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Location extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'location'; 

    protected $fillable = ['locsign','morada', 'codigo_postal','localidade','coordenadas'];
}