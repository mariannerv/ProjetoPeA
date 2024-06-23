<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    // Define relationships if necessary
    public function lostObjects()
    {
        return $this->hasMany(LostObject::class);
    }
}
