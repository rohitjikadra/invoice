<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryChallanMeter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'delivery_challan_id',
        'sr_no',
        'meter',
    ];

    protected $casts = [
        'meter' => 'decimal:2',
    ];

    public function challan(): BelongsTo
    {
        return $this->belongsTo(DeliveryChallan::class, 'delivery_challan_id');
    }
}
