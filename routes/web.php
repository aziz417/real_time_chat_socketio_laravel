<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('auth/{provider}', [SocialiteController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);

Route::middleware('auth')->group(function () {
    // Fetch the last 10 messages (for authenticated users only)
    Route::get('/chat/{user_id?}', [ChatController::class, 'chat'])
        ->name('chat');

    // Store a new message (for authenticated users only)
    Route::post('/messages', [ChatController::class, 'sendMessage'])
        ->name('messages.send');
});