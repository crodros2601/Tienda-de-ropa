<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = ['assigned_to', 'title', 'description', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class,'assigned_to');
    }
}
