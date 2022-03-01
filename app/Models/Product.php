<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'production_date',
        'expired_at',
        'cost',
        'image'
    ];

    public function fridge (): BelongsToMany
    {
        return $this->belongsToMany(Fridge::class, 'warehouses');
    }
}
