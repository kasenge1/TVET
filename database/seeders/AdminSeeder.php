<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin account
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@tvetrevision.com',
            'password' => Hash::make('password'), // Change this in production!
            'role' => 'admin',
            'subscription_tier' => 'premium',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@tvetrevision.com');
        $this->command->info('Password: password');
        $this->command->warn('Please change the password after first login!');
    }
}
