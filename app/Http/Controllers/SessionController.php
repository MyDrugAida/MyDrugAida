<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
  public function __construct() {}

  //check if user is authenticated
  public static function checkAuth() {
    if (!(session()->has('_uid') && !empty(session()->has('_uid')))) {
      session()->invalidate();
      redirect('/login');
      //prevent multitab or multidevice login by the same user 
    } else if (session('session_id') && session('session_id') !== session()->getId()) {
      session()->invalidate();
      redirect('/login');
    }
  }
  
  //check user role...check firebase
  public static function checkClearance(){
    
  }

//accepts the user_id(uid)...this is different from session_id
  public static function login($uid) {
    session(['_uid' => $uid, 'logged_in_at' => now()]);
    session()->regenerate();
    session()->put('session_id', session()->getId());
  }
  
   public static function logout() {
    if (session()->has('role')) {
      session()->forget('role');
    }
    if (session()->has('_uid')) {
      session()->forget('_uid');
    }
  }
  
}