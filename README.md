composer require Laravel/breeze
php artisan breeze:install 
Create a new database
Add database to your .env file
php artisan migrate

php artisan make:model -m Admin
Update guards and providers in auth.php (config)
Create Controllers for Admin




Mutiple guests authentication in Laravel
1. Define Your Guards and Providers
2. Create Models and Migrations
   php artisan make:model Admin -m
   php artisan make:model Guest -m
3. Edit the migration files to define the table structure
4. Make sure your Admin and Guest models use the Illuminate\Foundation\Auth\User trait
5. Setup Authentication Routes and Controllers
   e.g.	
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended('admin/dashboard');
        }
    }
6. Define routes in routes/web.php or routes/api.php for each authentication type
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm']);
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::get('dashboard', [AdminController::class, 'index'])->middleware('auth:admin');
});

// Guest routes
Route::prefix('user')->group(function () {
    Route::get('login', [UserAuthController::class, 'showLoginForm']);
    Route::post('login', [UserAuthController::class, 'login']);
    Route::get('dashboard', [USerController::class, 'index'])->middleware('auth:user');
});

7. Create views for each authentication type under resources/views/auth/admin and resources/views/auth/guest.
