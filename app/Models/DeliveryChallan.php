<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryChallan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'challan_number',
        'customer_id',
        'challan_date',
        'vehicle_no',
        'eway_bill_no',
        'quality',
        'broker',
        'receiver_name',
        'receiver_address',
        'receiver_gstin',
        'consignee_name',
        'consignee_address',
        'consignee_gstin',
        'remark',
    ];

    protected $casts = [
        'challan_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function meters(): HasMany
    {
        return $this->hasMany(DeliveryChallanMeter::class)->orderBy('sr_no');
    }
}
