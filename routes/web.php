<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PharmacistController;
use App\Services\FirebaseService;


Route::get('/register', function() {
  return view('register_practitioner');
});
Route::get('/login', function() {
  return view('login_practitioner');
})->middleware('web');
//AUTHENTICATION
//Email & Password Login
Route::post('/signup/{role}', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('web');
Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/practitioner/verifyEmail', [AuthController::class, 'verifyEmail']);
Route::get('/practitioner/resetPassword', [AuthController::class, 'resetPassword']);



//Google Login
Route::get('/test_google_auth', function() {
  return view('google_auth');
});
Route::get('auth/google/{role}', [AuthController::class, 'redirectToGoogle']);
Route::get('google_redirect_location', [AuthController::class, 'GoogleLogin']);



//for testing only,th controller method should be normally called during patient sign up
Route::get('/set_patient_role', function() {
  return view('set_patient_role');
});
Route::post('/set_patient_role', [PermissionController::class, 'setPatientRole']);

//guide this route! CPanel Level Clearance required
Route::get('/distinguish_user_role', function () {
  return view('distinguish_user_role');
});
Route::post('/distinguish_user_role', [PermissionController::class, 'distinguishUserRole']);

//for Users(Pharmacists and Doctors)
Route::get('/load_prescriptions',[UserController::class,'loadPrescriptions']);
Route::get('/load_prescription_for_a_patient/{patient_uid}',[UserController::class,'loadPrescriptionForAPatient']);


//for Pharmacists alone
Route::get('/flag_prescription/{id}',[PharmacistController::class,'flagPrescription']);


Route::get('/test', function(){
  return view('test');
})
?>