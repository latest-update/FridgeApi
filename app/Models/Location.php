<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Location extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'city',
        'district',
        'name',
        'latitude',
        'longitude'
    ];

    public function fridge(): HasOne
    {
        return $this->hasOne(Fridge::class);
    }
}
