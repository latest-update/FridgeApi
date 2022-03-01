<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'fridge_id',
        'count'
    ];

    public function fridge () : BelongsTo
    {
        return $this->belongsTo(Fridge::class);
    }

    public function product () : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
