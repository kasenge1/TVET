<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class InstallController extends Controller
{
    /**
     * Required PHP extensions
     */
    protected array $requiredExtensions = [
        'bcmath',
        'ctype',
        'curl',
        'dom',
        'fileinfo',
        'json',
        'mbstring',
        'openssl',
        'pdo',
        'pdo_mysql',
        'tokenizer',
        'xml',
    ];

    /**
     * Required PHP version
     */
    protected string $requiredPhpVersion = '8.2.0';

    /**
     * Writable directories
     */
    protected array $writableDirectories = [
        'storage/app',
        'storage/framework',
        'storage/logs',
        'bootstrap/cache',
    ];

    /**
     * Step 1: Welcome page
     */
    public function welcome()
    {
        return view('install.welcome');
    }

    /**
     * Step 2: Requirements check
     */
    public function requirements()
    {
        $requirements = $this->checkRequirements();
        $canProceed = $requirements['php_version']['status']
            && $requirements['extensions']['all_installed']
            && $requirements['directories']['all_writable'];

        return view('install.requirements', compact('requirements', 'canProceed'));
    }

    /**
     * Step 3: Database configuration form
     */
    public function database()
    {
        return view('install.database');
    }

    /**
     * Step 3: Process database configuration
     */
    public function databaseStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'db_host' => 'required|string',
            'db_port' => 'required|numeric',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Test database connection
        $connectionTest = $this->testDatabaseConnection(
            $request->db_host,
            $request->db_port,
            $request->db_database,
            $request->db_username,
            $request->db_password ?? ''
        );

        if (!$connectionTest['success']) {
            return back()->with('error', $connectionTest['message'])->withInput();
        }

        // Store in session for later use (encrypt sensitive data)
        session([
            'install.db_host' => $request->db_host,
            'install.db_port' => $request->db_port,
            'install.db_database' => $request->db_database,
            'install.db_username' => $request->db_username,
            'install.db_password' => $request->db_password ? Crypt::encryptString($request->db_password) : '',
        ]);

        return redirect()->route('install.application');
    }

    /**
     * Step 4: Application settings form
     */
    public function application()
    {
        if (!session('install.db_host')) {
            return redirect()->route('install.database')
                ->with('error', 'Please configure database first.');
        }

        return view('install.application');
    }

    /**
     * Step 4: Process application settings
     */
    public function applicationStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'app_timezone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        session([
            'install.app_name' => $request->app_name,
            'install.app_url' => rtrim($request->app_url, '/'),
            'install.app_timezone' => $request->app_timezone,
        ]);

        return redirect()->route('install.admin');
    }

    /**
     * Step 5: Admin account form
     */
    public function admin()
    {
        if (!session('install.app_name')) {
            return redirect()->route('install.application')
                ->with('error', 'Please configure application settings first.');
        }

        return view('install.admin');
    }

    /**
     * Step 5: Process admin account and finalize installation
     */
    public function adminStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ], [
            'admin_password.uncompromised' => 'This password has been compromised in data breaches. Please choose a different password.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Store admin data (encrypt password in session)
        session([
            'install.admin_name' => $request->admin_name,
            'install.admin_email' => $request->admin_email,
            'install.admin_password' => Crypt::encryptString($request->admin_password),
        ]);

        return redirect()->route('install.finalize');
    }

    /**
     * Step 6: Finalize installation
     */
    public function finalize()
    {
        if (!session('install.admin_email')) {
            return redirect()->route('install.admin')
                ->with('error', 'Please create admin account first.');
        }

        return view('install.finalize');
    }

    /**
     * Process final installation
     */
    public function process(Request $request)
    {
        try {
            // 1. Update .env file
            $this->updateEnvFile();

            // 2. Clear config cache to use new .env values
            Artisan::call('config:clear');

            // 3. Reconnect to database with new credentials
            $this->reconnectDatabase();

            // 4. Run migrations
            Artisan::call('migrate', ['--force' => true]);

            // 5. Run seeders
            Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder', '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'SubscriptionPackageSeeder', '--force' => true]);

            // 6. Create admin user
            $this->createAdminUser();

            // 7. Create storage link
            if (!file_exists(public_path('storage'))) {
                Artisan::call('storage:link');
            }

            // 8. Clear all caches
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');

            // 9. Create installation lock file
            $this->createInstallLock();

            // 10. Clear installation session
            $this->clearInstallSession();

            return response()->json([
                'success' => true,
                'message' => 'Installation completed successfully!',
                'redirect' => route('login'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Installation failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Installation complete page
     */
    public function complete()
    {
        return view('install.complete');
    }

    /**
     * Check all requirements
     */
    protected function checkRequirements(): array
    {
        return [
            'php_version' => $this->checkPhpVersion(),
            'extensions' => $this->checkExtensions(),
            'directories' => $this->checkDirectories(),
        ];
    }

    /**
     * Check PHP version
     */
    protected function checkPhpVersion(): array
    {
        $currentVersion = PHP_VERSION;
        $status = version_compare($currentVersion, $this->requiredPhpVersion, '>=');

        return [
            'required' => $this->requiredPhpVersion,
            'current' => $currentVersion,
            'status' => $status,
        ];
    }

    /**
     * Check PHP extensions
     */
    protected function checkExtensions(): array
    {
        $extensions = [];
        $allInstalled = true;

        foreach ($this->requiredExtensions as $extension) {
            $installed = extension_loaded($extension);
            $extensions[$extension] = $installed;
            if (!$installed) {
                $allInstalled = false;
            }
        }

        return [
            'list' => $extensions,
            'all_installed' => $allInstalled,
        ];
    }

    /**
     * Check writable directories
     */
    protected function checkDirectories(): array
    {
        $directories = [];
        $allWritable = true;

        foreach ($this->writableDirectories as $directory) {
            $path = base_path($directory);
            $writable = is_writable($path);
            $directories[$directory] = $writable;
            if (!$writable) {
                $allWritable = false;
            }
        }

        return [
            'list' => $directories,
            'all_writable' => $allWritable,
        ];
    }

    /**
     * Test database connection
     */
    protected function testDatabaseConnection($host, $port, $database, $username, $password): array
    {
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$database}";
            $pdo = new \PDO($dsn, $username, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return ['success' => true, 'message' => 'Connection successful'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Update .env file with installation settings
     */
    protected function updateEnvFile(): void
    {
        $envPath = base_path('.env');
        $envExamplePath = base_path('.env.example');

        // If .env doesn't exist, copy from .env.example
        if (!File::exists($envPath)) {
            File::copy($envExamplePath, $envPath);
        }

        $envContent = File::get($envPath);

        // Generate new app key
        $appKey = 'base64:' . base64_encode(random_bytes(32));

        // Decrypt database password from session
        $dbPassword = session('install.db_password');
        if ($dbPassword) {
            try {
                $dbPassword = Crypt::decryptString($dbPassword);
            } catch (\Exception $e) {
                // Password might not be encrypted
            }
        }

        // Update values
        $replacements = [
            'APP_NAME' => '"' . session('install.app_name') . '"',
            'APP_URL' => session('install.app_url'),
            'APP_TIMEZONE' => session('install.app_timezone'),
            'APP_KEY' => $appKey,
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
            'LOG_LEVEL' => 'warning',
            'DB_HOST' => session('install.db_host'),
            'DB_PORT' => session('install.db_port'),
            'DB_DATABASE' => session('install.db_database'),
            'DB_USERNAME' => session('install.db_username'),
            'DB_PASSWORD' => '"' . ($dbPassword ?? '') . '"',
        ];

        foreach ($replacements as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        File::put($envPath, $envContent);
    }

    /**
     * Reconnect to database with new credentials
     */
    protected function reconnectDatabase(): void
    {
        $dbPassword = session('install.db_password');
        if ($dbPassword) {
            try {
                $dbPassword = Crypt::decryptString($dbPassword);
            } catch (\Exception $e) {
                // Password might not be encrypted (legacy)
            }
        }

        config([
            'database.connections.mysql.host' => session('install.db_host'),
            'database.connections.mysql.port' => session('install.db_port'),
            'database.connections.mysql.database' => session('install.db_database'),
            'database.connections.mysql.username' => session('install.db_username'),
            'database.connections.mysql.password' => $dbPassword ?? '',
        ]);

        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    /**
     * Create admin user
     */
    protected function createAdminUser(): void
    {
        // Decrypt admin password from session
        $adminPassword = session('install.admin_password');
        if ($adminPassword) {
            try {
                $adminPassword = Crypt::decryptString($adminPassword);
            } catch (\Exception $e) {
                // Password might not be encrypted
            }
        }

        $user = User::create([
            'name' => session('install.admin_name'),
            'email' => session('install.admin_email'),
            'password' => Hash::make($adminPassword),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Assign admin role using Spatie Permission
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
            $user->assignRole($adminRole);
        }
    }

    /**
     * Create installation lock file
     */
    protected function createInstallLock(): void
    {
        $lockFile = storage_path('installed');
        File::put($lockFile, json_encode([
            'installed_at' => now()->toIso8601String(),
            'version' => config('app.version', '1.0.0'),
        ]));
    }

    /**
     * Clear installation session data
     */
    protected function clearInstallSession(): void
    {
        session()->forget([
            'install.db_host',
            'install.db_port',
            'install.db_database',
            'install.db_username',
            'install.db_password',
            'install.app_name',
            'install.app_url',
            'install.app_timezone',
            'install.admin_name',
            'install.admin_email',
            'install.admin_password',
        ]);
    }

    /**
     * Check if application is already installed
     */
    public static function isInstalled(): bool
    {
        return File::exists(storage_path('installed'));
    }
}
