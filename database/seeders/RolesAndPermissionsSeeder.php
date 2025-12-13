<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Course permissions
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',
            'publish courses',

            // Unit permissions
            'view units',
            'create units',
            'edit units',
            'delete units',

            // Question permissions
            'view questions',
            'create questions',
            'edit questions',
            'delete questions',
            'import questions',
            'export questions',

            // Level permissions
            'view levels',
            'create levels',
            'edit levels',
            'delete levels',

            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            'impersonate users',
            'block users',

            // Role permissions (consolidated)
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'manage roles', // Combined permission for full role management

            // Blog permissions
            'view blog',
            'create blog',
            'edit blog',
            'delete blog',
            'publish blog',

            // Blog category permissions
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',

            // Subscription permissions
            'view subscriptions',
            'create subscriptions',
            'edit subscriptions',
            'delete subscriptions',
            'manage packages',
            'manage subscriptions', // Combined permission for full subscription management

            // Analytics permissions
            'view analytics',
            'export analytics',

            // Settings & Admin
            'view dashboard',
            'view activity logs',
            'clear activity logs',
            'send notifications',

            // Settings permissions (granular)
            'manage settings',          // General settings (site name, description, etc.)
            'manage branding',          // Logo, favicon, colors
            'manage contact settings',  // Contact info, address
            'manage social settings',   // Social media links
            'manage payment settings',  // M-Pesa, payment gateways
            'manage email settings',    // SMTP, email configuration
            'manage ai settings',       // AI provider, API keys
            'manage ads settings',      // Google Ads configuration
            'manage security settings', // reCAPTCHA, email verification
            'manage feature settings',  // Subscriptions, appearance, PWA
            'manage packages',          // Subscription packages
            'manage hero settings',     // Homepage hero section

            // System permissions (super-admin only)
            'manage maintenance',       // Maintenance mode
            'view system info',         // System cache, PHP info
            'clear cache',              // Clear application cache
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin - has all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - can manage everything except sensitive system settings, roles management, and impersonation
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo([
            // Content management
            'view courses', 'create courses', 'edit courses', 'delete courses', 'publish courses',
            'view units', 'create units', 'edit units', 'delete units',
            'view questions', 'create questions', 'edit questions', 'delete questions', 'import questions', 'export questions',
            'view levels', 'create levels', 'edit levels', 'delete levels',
            // Blog management
            'view blog', 'create blog', 'edit blog', 'delete blog', 'publish blog',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            // User management (limited - no delete, no roles management)
            'view users', 'create users', 'edit users', 'block users',
            'view roles', // Can view roles but not manage them
            // Subscriptions (can view and manage)
            'view subscriptions', 'edit subscriptions', 'manage subscriptions', 'manage packages',
            // Dashboard & Reports
            'view dashboard', 'view activity logs', 'view analytics',
            'send notifications',
            // Settings (safe ones only - no payment, email, AI, security, or system)
            'manage settings',          // General settings
            'manage branding',          // Branding/logos
            'manage contact settings',  // Contact info
            'manage social settings',   // Social media
            'manage hero settings',     // Hero section
            'manage feature settings',  // Feature toggles
            'manage ads settings',      // Ads settings
        ]);

        // Content Manager - can manage courses, units, questions, and blog
        $contentManager = Role::firstOrCreate(['name' => 'content-manager']);
        $contentManager->givePermissionTo([
            'view courses', 'create courses', 'edit courses',
            'view units', 'create units', 'edit units',
            'view questions', 'create questions', 'edit questions', 'delete questions', 'import questions', 'export questions',
            'view levels',
            'view blog', 'create blog', 'edit blog', 'publish blog',
            'view categories', 'create categories', 'edit categories',
            'view dashboard', 'view analytics',
        ]);

        // Question Editor - can only manage questions (for helpers adding questions)
        $questionEditor = Role::firstOrCreate(['name' => 'question-editor']);
        $questionEditor->givePermissionTo([
            'view courses',
            'view units',
            'view questions', 'create questions', 'edit questions', 'import questions',
            'view levels',
            'view dashboard',
        ]);

        // Blog Editor - can manage blog content only
        $blogEditor = Role::firstOrCreate(['name' => 'blog-editor']);
        $blogEditor->givePermissionTo([
            'view blog', 'create blog', 'edit blog', 'publish blog',
            'view categories', 'create categories', 'edit categories',
            'view dashboard',
        ]);

        // Subscription Manager - can manage subscriptions and packages
        $subscriptionManager = Role::firstOrCreate(['name' => 'subscription-manager']);
        $subscriptionManager->givePermissionTo([
            'view subscriptions', 'create subscriptions', 'edit subscriptions', 'delete subscriptions',
            'manage packages',
            'view users',
            'view dashboard', 'view analytics',
        ]);

        // Student - basic user role
        $student = Role::firstOrCreate(['name' => 'student']);
        // Students don't need admin permissions, they use the frontend

        // Migrate existing users to new roles
        $this->migrateExistingUsers();
    }

    /**
     * Migrate existing users from role column to Spatie roles
     */
    private function migrateExistingUsers(): void
    {
        // Migrate admins
        User::where('role', 'admin')->each(function ($user) {
            if (!$user->hasRole('super-admin') && !$user->hasRole('admin')) {
                $user->assignRole('admin');
            }
        });

        // Migrate students
        User::where('role', 'student')->each(function ($user) {
            if (!$user->hasRole('student')) {
                $user->assignRole('student');
            }
        });
    }
}
