<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class LostObject extends Eloquent
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
