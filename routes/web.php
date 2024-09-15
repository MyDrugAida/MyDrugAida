<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\AuthController;

Route::get('/practitioners/register', function() {
  return view('register_practitioner');
});
Route::get('/practitioners/login', function() {
  return view('login_practitioner');
});

//Email & Password Login
Route::post('/practitioner/signup', [AuthController::class, 'register']);
Route::post('/practitioner/login', [AuthController::class, 'login']);
Route::get('/practitioner/logout', [AuthController::class, 'logout']);

Route::get('/practitioner/verifyEmail',[AuthController::class,'verifyEmail']);
Route::get('/practitioner/resetPassword',[AuthController::class,'resetPassword']);



//Google Login
Route::get('/test_google_auth', function(){
  return view('google_auth');
});
Route::get('auth/google', [AuthController::class, 'redirectToGoogle']);

Route::get('google_redirect_location', [AuthController::class,'GoogleLogin']);
//Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

//Route::get('/realtimedatabase', [FirebaseController::class,'realtimedatabase']);

?>