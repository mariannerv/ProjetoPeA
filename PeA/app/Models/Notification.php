<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'notifications'; 

    protected $fillable = [
        'user_id', 'type', 'data', 'read_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
