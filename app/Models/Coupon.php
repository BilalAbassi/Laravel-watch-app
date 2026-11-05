<?php

namespace App\Models;

use App\Enums\CouponType;
use Database\Factories\CouponFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'type', 'value', 'min_spend', 'expires_at', 'is_active'])]
class Coupon extends Model
{
    /** @use HasFactory<CouponFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => CouponType::class,
            'value' => 'decimal:2',
            'min_spend' => 'decimal:2',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Only coupons that are switched on and not expired.
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true)
            ->where(function (Builder $query): void {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Find a working coupon by the code the customer typed.
     */
    public static function findActiveByCode(string $code): ?self
    {
        return static::query()->active()->where('code', strtoupper(trim($code)))->first();
    }

    /**
     * How much money this coupon takes off the given subtotal (never more than the subtotal).
     */
    public function discountFor(float $subtotal): float
    {
        if ($subtotal < (float) $this->min_spend) {
            return 0.0;
        }

        $discount = match ($this->type) {
            CouponType::Percent => $subtotal * (float) $this->value / 100,
            CouponType::Fixed => (float) $this->value,
        };

        return round(min($discount, $subtotal), 2);
    }
}
