<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EtimsInvoice extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
