<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'location';
    protected $primaryKey = '_id';
    protected $fillable = [
        'rua',
        'freguesia',
        'municipio',
        'distrito',
        'codigo_postal',
        'pais',
        'coordenadas', 
    ];

}
