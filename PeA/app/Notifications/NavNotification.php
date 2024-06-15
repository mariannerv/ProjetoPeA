<?php
namespace App\Models\Notifications;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class NavNotification extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'notifications';
    protected $fillable = ['user_id', 'title', 'type', 'body', 'is_read', 'read_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User'); 
    }
}
