<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\Model;

class lostObject extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'lostObject';
    protected $primaryKey = '_id';
    protected $fillable = [
        'owner_id',
        'ownerEmail',
        'description',
        'date_lost',
        'brand',
        'color',
        'size',
        'category',
        'location_id'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
