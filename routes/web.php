<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Home\HomeController;
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

Route::get('/',[HomeController::class, 'index']);


Route::get('/test-welcome', function(){
    // Create a mock user object with your actual database fields
    $user = new stdClass();
    $user->name = 'John Doe';
    $user->username = 'johndoe123';
    $user->email = 'john@example.com';
    $user->points = 100;
    $user->followers_count = 5;
    $user->posts_count = 2;
    $user->referral_code = 'ABC123';
    $user->created_at = now();
    
    $appName = config('app.name', 'CyberForum');
    
    return view('emails.welcome', [
        'user' => $user,
        'appName' => $appName,
    ]);
});

Route::get('/test-notification', function(){
    $messageData = [
        'subject' => 'New Referral Success',
        'user_name' => 'James Munir',
        'response' => 'Congratulations! CHIBUIKE OKOYE just joined using your referral code. You\'ve earned 200 points!',
        'type' => 'success',
        'action_url' => url('/dashboard'),
        'action_text' => 'View Dashboard'
    ];
    
    $appName = config('app.name', 'CyberForum');
    
    return view('emails.general-notification', [
        'messageData' => $messageData,
        'appName' => $appName,
    ]);
});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


Route::get('/referrer-info/{code}', function($code) {
    $referrer = \App\Models\User::where('referral_code', $code)->first(['name', 'username']);
    
    if ($referrer) {
        return response()->json([
            'success' => true,
            'referrer' => [
                'name' => $referrer->name,
                'username' => $referrer->username
            ]
        ]);
    }
    
    return response()->json([
        'success' => false,
        'message' => 'Referrer not found'
    ]);
});
require __DIR__.'/auth.php';
