<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroceryList extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'title'
    ];

    // A GroceryList belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A GroceryList has many GroceryItems
    public function items()
    {
        return $this->hasMany(GroceryItem::class);
    }
}
