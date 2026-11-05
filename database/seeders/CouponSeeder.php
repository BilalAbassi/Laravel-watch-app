<?php

namespace Database\Seeders;

use App\Enums\CouponType;
use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Seed default store discount coupons. Safe to run multiple times.
     */
    public function run(): void
    {
        Coupon::updateOrCreate(
            ['code' => 'TIMEYRAH10'],
            [
                'type' => CouponType::Percent,
                'value' => 10.00,
                'min_spend' => 0.00,
                'expires_at' => null,
                'is_active' => true,
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'VIP2026'],
            [
                'type' => CouponType::Fixed,
                'value' => 200.00,
                'min_spend' => 0.00,
                'expires_at' => null,
                'is_active' => true,
            ]
        );
    }
}
