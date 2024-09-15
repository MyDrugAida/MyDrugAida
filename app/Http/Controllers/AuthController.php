<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\EmailExists;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Kreait\Firebase\Auth\Token\Exception\InvalidToken;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
  protected $auth;
  protected $google_sign_in;

  public function __construct() {
    $this->auth = app('firebase')->createAuth();
  }

  // Register practitioner
  public function register(Request $request) {
    $userProperties = [
      'email' => $request->input('email'),
      'emailVerified' => false,
      'password' => $request->input('password'),
      'displayName' => $request->input('name'),
      'dob' => $request->input('dob'),
      'disabled' => false,
    ];

    try {
      $user = $this->auth->createUser($userProperties);
      if ($this->verifyEmail($request->input('email'))) {
        echo "Verification link sent";
        return response()->json(['status' => 'success', 'user' => $user]);
      }
    } catch (\Kreait\Firebase\Exception\Auth\EmailExists $e) {
      return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
  }

  // Login practitioner
  public function login(Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');
    try {
      $signInResult = $auth->signInWithEmailAndPassword($email, $password);
      $idToken = $signInResult->idToken();
      session(['_token' => $idToken]); //to keep the user signed in.will be check constantly elsewhere
      // Verify the ID token and get the user
      $verifiedIdToken = $this->auth->verifyIdToken($idToken);
      $uid = $verifiedIdToken->claims()->get('sub'); // 'sub' is the UID claim
      // Fetch user data from Firebase
      $user = $this->auth->getUser($uid);
      return response()->json(['user' => $user]);
    } catch (\Exception $e) {
      return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
  }

  protected function verifyEmail($email = null) {
    $user = $this->auth->getUserByEmail('anjolaakinsoyinu@gmail.com');
    try {
      $this->auth->sendEmailVerificationLink($user->email);
      return true;
    }catch(\Exception $e) {}
  }

  public function resetPassword() {
    try {
      //$this->auth->sendPasswordResetLink('anjolaakinsoyinu@gmail.com');
      $this->auth->sendPasswordResetLink($request->input('email'));
      return "Password Reset Link Sent";
    }catch(\Exception $e) {
      echo $e->getMessage();
    }
  }

  public function verifyToken($token) {
    try {
      $verifiedIdToken = $this->auth->verifyIdToken($token);
      return response()->json(['status' => 'valid', 'data' => $verifiedIdToken]);
    } catch (InvalidToken $e) {
      return response()->json(['status' => 'invalid', 'message' => $e->getMessage()]);
    }
  }


  public function redirectToGoogle() {
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback() {
    $user = Socialite::driver('google')->user();
  }

  public function GoogleLogin() {
    try {
      // After login, get user from Socialite/Firebase
      $auth = app('firebase')->createAuth();
      $google_user = Socialite::driver('google')->user();
      $google_user = json_decode(json_encode($google_user),true);
      //create a account for this Google account on first ever access
      $userProperties = [
        'email' => $google_user["email"],
        'emailVerified' => true,
        'password' => 'GoogleLoginNotRequired',
        'displayName' => $google_user["name"],
        'dob' => 'null',
        'disabled' => false,
      ];
      try {
        $user = $auth->createUser($userProperties);//creates a account for the google user for the first,if account already exists moves on to the catch block
        $signInResult = $auth->signInWithEmailAndPassword($google_user["email"], "GoogleLoginNotRequired");
        $idToken = $signInResult->idToken();
        session(['_token' => $idToken]); //to keep the user signed in.will be check constantly elsewhere
        // Verify the ID token and get the user
        $verifiedIdToken = $this->auth->verifyIdToken($idToken);
        $uid = $verifiedIdToken->claims()->get('sub'); // 'sub' is the UID claim
        // Fetch user data from Firebase
        $user = $this->auth->getUser($uid);
        return response()->json(['user' => $user]);
      } catch (\Kreait\Firebase\Exception\Auth\EmailExists $e) {
        $signInResult = $auth->signInWithEmailAndPassword($google_user["email"], "GoogleLoginNotRequired");
        $idToken = $signInResult->idToken();
        session(['_token' => $idToken]); //to keep the user signed in.will be check constantly elsewhere
        // Verify the ID token and get the user
        $verifiedIdToken = $this->auth->verifyIdToken($idToken);
        $uid = $verifiedIdToken->claims()->get('sub'); // 'sub' is the UID claim
        // Fetch user data from Firebase
        $user = $this->auth->getUser($uid);
        return response()->json(['user' => $user]);
      }
      
    }catch(\Exception $e) {
      echo $e->getMessage();
    }
  }

}
?>