<?php

use App\Http\Controllers\Api\MpesaCallbackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// M-Pesa Callback (no authentication required)
Route::post('/mpesa/callback', [MpesaCallbackController::class, 'handleCallback']);
