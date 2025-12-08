<?php

namespace Database\Seeders;

use App\Models\SubscriptionPackage;
use Illuminate\Database\Seeder;

class SubscriptionPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Weekly Package
        SubscriptionPackage::updateOrCreate(
            ['slug' => 'weekly-plan'],
            [
                'name' => 'Weekly Plan',
                'description' => 'Enjoy ad-free learning for a week',
                'price' => 99.00,
                'duration_days' => 7,
                'features' => [
                    'Ad-free experience',
                ],
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 1,
            ]
        );

        // Monthly Package
        SubscriptionPackage::updateOrCreate(
            ['slug' => 'monthly-plan'],
            [
                'name' => 'Monthly Plan',
                'description' => 'Enjoy ad-free learning for a month',
                'price' => 299.00,
                'duration_days' => 30,
                'features' => [
                    'Ad-free experience',
                ],
                'is_active' => true,
                'is_popular' => true,
                'sort_order' => 2,
            ]
        );

        // Yearly Package
        SubscriptionPackage::updateOrCreate(
            ['slug' => 'yearly-plan'],
            [
                'name' => 'Yearly Plan',
                'description' => 'Enjoy ad-free learning for a full year',
                'price' => 1499.00,
                'duration_days' => 365,
                'features' => [
                    'Ad-free experience',
                ],
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 3,
            ]
        );
    }
}
