<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class NotifLog extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'notifications'; 
    protected $fillable = ['user_id', 'content', 'read'];

}
