<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Notification extends Eloquent
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'notifications'; 

    protected $fillable = [
        'user_id', 'type', 'data', 'read_at'
    ];

    // Define a relação com o modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
