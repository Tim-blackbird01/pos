<?php

namespace Modules\Superadmin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'custom_permissions' => 'array',
    ];

    /**
     * Scope a query to only include active packages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Returns the list of active pakages
     *
     * @return object
     */
    public static function listPackages($exlude_private = false, $interval = null)
    {
        $packages = Package::active()
                        ->orderby('sort_order');

        if ($exlude_private) {
            $packages->notPrivate();
        }

        if (!empty($interval)) {
            $packages->where('interval', $interval);
        }

        return $packages->get();
    }

    /**
     * Scope a query to exclude private packages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotPrivate($query)
    {
        return $query->where('is_private', 0);
    }

    /**
     * Return a non-persisted annual version of a monthly package.
     * Annual price is calculated from the package's configured discount.
     */
    public function billedAnnually()
    {
        if ($this->interval !== 'months' || (int) $this->interval_count < 1) {
            return $this;
        }

        $annualPackage = clone $this;
        $discount = $this->annualDiscountPercentage();
        $annualPackage->price = round(
            ((float) $this->price / (int) $this->interval_count) * 12 * (1 - ($discount / 100)),
            2
        );
        $annualPackage->interval = 'years';
        $annualPackage->interval_count = 1;
        $annualPackage->billing_cycle = 'annual';

        return $annualPackage;
    }

    public function annualDiscountPercentage()
    {
        return max(0, min(100, (float) $this->annual_discount_percentage));
    }

    public function supportsAnnualBilling()
    {
        return $this->interval === 'months' && (int) $this->interval_count > 0;
    }
}
