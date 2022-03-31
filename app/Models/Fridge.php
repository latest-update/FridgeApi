<?php

namespace App\Models;

use App\Models\Custom\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Fridge extends Model
{
    use HasFactory, HasUuid;

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'location_id',
        'mode_id',
        'tfid'
    ];

    protected $hidden = [
        'tfid'
    ];

    public function location() : BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function mode() : BelongsTo
    {
        return $this->belongsTo(Mode::class);
    }

    public function products (): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'warehouses')->withPivot('count');
    }

    public function warehouse (): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }
}
