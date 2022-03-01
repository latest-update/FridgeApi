<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mode extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'mode'
    ];

    public function fridges () : HasMany
    {
        return $this->hasMany(Fridge::class);
    }
}
