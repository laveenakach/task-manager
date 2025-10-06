<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'completed',
        'position',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('position', 'asc')->orderBy('created_at','asc');
    }
}
