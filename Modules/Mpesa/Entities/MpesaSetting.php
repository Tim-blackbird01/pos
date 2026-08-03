<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;

class MpesaSetting extends Model
{
    protected $table = 'mpesa_settings';

    protected $guarded = ['id'];

    /**
     * Encrypt sensitive fields before saving and decrypt when reading.
     * This way the consumer secret / passkey are not stored as plain text.
     */
    protected function consumerKey(): Attribute
    {
        return $this->cryptAttribute();
    }

    protected function consumerSecret(): Attribute
    {
        return $this->cryptAttribute();
    }

    protected function passkey(): Attribute
    {
        return $this->cryptAttribute();
    }

    protected function securityCredential(): Attribute
    {
        return $this->cryptAttribute();
    }

    protected function initiatorName(): Attribute
    {
        return $this->cryptAttribute();
    }

    private function cryptAttribute(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (empty($value)) {
                    return $value;
                }
                try {
                    return Crypt::decryptString($value);
                } catch (\Exception $e) {
                    // Value wasn't encrypted (e.g. legacy/plain), return as-is
                    return $value;
                }
            },
            set: function ($value) {
                if (empty($value)) {
                    return $value;
                }
                return Crypt::encryptString($value);
            },
        );
    }

    /**
     * Helper: get settings for a business, or null if not configured.
     */
    public static function getForBusiness(int $businessId): ?self
    {
        return self::where('business_id', $businessId)->first();
    }
}
