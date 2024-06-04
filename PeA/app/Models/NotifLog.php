<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class NotifLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'notifications'; 
    protected $fillable = ['user_id', 'content', 'read'];
    protected $casts = [
        'objectId' => 'string', 
    ];
}
