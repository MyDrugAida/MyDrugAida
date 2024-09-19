<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\AuthController;
use App\Services\FirebaseService;


Route::get('/register', function() {
  return view('register_practitioner');
});
Route::get('/login', function() {
  return view('login_practitioner');
});
//AUTHENTICATION
//Email & Password Login
Route::post('/signup/{role}', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/practitioner/verifyEmail', [AuthController::class, 'verifyEmail']);
Route::get('/practitioner/resetPassword', [AuthController::class, 'resetPassword']);



//Google Login
Route::get('/test_google_auth', function() {
  return view('google_auth');
});
Route::get('auth/google/{role}', [AuthController::class, 'redirectToGoogle']);
Route::get('google_redirect_location', [AuthController::class, 'GoogleLogin']);

Route::get('test', function() {
  $firebase = new FirebaseService;
  $firebase->storeUserData(1234555, [
    'email' => "nn@gmail.com"
    // Add other data fields as needed
  ]);
});


//FILL DETAILS AFTER AUTHENTICATION
Route::get('fill_details',function(){
  //return view(
});
?>