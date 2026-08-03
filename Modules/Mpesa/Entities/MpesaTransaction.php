<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Eloquent\Model;

class MpesaTransaction extends Model
{
    protected $table = 'mpesa_transactions';

    protected $guarded = ['id'];

    protected $casts = [
        'raw_callback' => 'array',
    ];

    public function scopeForBusiness($query, int $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
