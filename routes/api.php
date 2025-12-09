<?php

use App\Http\Controllers\Api\MpesaCallbackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// M-Pesa Callback with rate limiting and IP validation
// Safaricom's IP ranges should be validated in the controller
Route::post('/mpesa/callback', [MpesaCallbackController::class, 'handleCallback'])
    ->middleware('throttle:60,1'); // 60 requests per minute max
