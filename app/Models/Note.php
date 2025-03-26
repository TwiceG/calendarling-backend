<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'date',
        'note',
        'user_id'
    ];

    // A Note belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
