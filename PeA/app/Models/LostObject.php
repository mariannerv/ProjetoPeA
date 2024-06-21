<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostObject extends Model
{
    use HasFactory;

    protected $fillable = [
        'ownerEmail',
        'description',
        'date_lost',
        'brand',
        'color',
        'size',
        'category_id',
        'location_id'
    ];

    public function category()
    {
        return $this->belongsTo(Categoria::class, 'category_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
