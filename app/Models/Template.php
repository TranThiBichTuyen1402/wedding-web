<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'view_path',
        'view',
        'thumbnail',
        'is_vip',
        'status',
    ];

    protected $casts = [
        'is_vip' => 'boolean',
        'status' => 'boolean',
    ];

    public function weddingCards()
    {
        return $this->hasMany(WeddingCard::class);
    }
}