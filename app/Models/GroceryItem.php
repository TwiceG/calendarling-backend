<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroceryItem extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'grocery_list_id',
        'item_name',
        'is_checked',
        'position'
    ];

    protected $casts = [
        'is_checked' => 'boolean'
    ];

    // A GroceryItem belongs to a GroceryList
    public function groceryList()
    {
        return $this->belongsTo(GroceryList::class);
    }
}
