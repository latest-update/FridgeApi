<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Operation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'fridge_id',
        'time',
        'purchased_price'
    ];

    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fridge () : BelongsTo
    {
        return $this->belongsTo(Fridge::class);
    }

    public function purchased_products () : HasMany
    {
        return $this->hasMany(Purchased_product::class);
    }

    public function products (): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'purchased_products')->withPivot('purchased_count');
    }

}
