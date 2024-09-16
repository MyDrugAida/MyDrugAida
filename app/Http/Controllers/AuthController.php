<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\EmailExists;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Kreait\Firebase\Auth\Token\Exception\InvalidToken;
use App\Http\Controllers\Controller;
use App\Services\FirebaseRealtimeDatabaseService;


class AuthController extends Controller
{
  protected $auth;
  protected $google_sign_in;

  public function __construct(Request $request) {
    $this->auth = app('firebase')->createAuth();
    session(['role' => $request->route('role')]);
  }

  // Register practitioner
  public function register(Request $request) {
    $userProperties = [
      'email' => $request->input('email'),
      'emailVerified' => false,
      'password' => $request->input('password'),
      'displayName' => $request->input('name'),
      'dob' => $request->input('dob'),
      'role' => 'pharmacist',
      //change later
      'disabled' => false,
    ];

    try {
      $user = $this->auth->createUser($userProperties);
      if ($this->verifyEmail($request->input('email'))) {
        session(['emailVerified' => 'false']);
        echo "<script> alert('Verification link sent');</script> ";

        return $this->login($request);
        //return response()->json(['status' => 'success', 'user' => $user]);
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
      $auth = app('firebase')->createAuth();
      $signInResult = $auth->signInWithEmailAndPassword($email, $password);
      $idToken = $signInResult->idToken();
      // Verify the ID token and get the user
      $verifiedIdToken = $this->auth->verifyIdToken($idToken);
      $uid = $verifiedIdToken->claims()->get('sub'); // 'sub' is the UID claim
      // Fetch user data from Firebase
      session(['_uid' => $uid]); //to keep the user signed in.will be check constantly elsewhere
      //$user = $this->auth->getUser($uid);
      return $this->check($uid);
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
      $google_user = json_decode(json_encode($google_user), true);
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
        $user = $auth->createUser($userProperties); //creates a account for the google user for the first,if account already exists moves on to the catch block
        $signInResult = $auth->signInWithEmailAndPassword($google_user["email"], "GoogleLoginNotRequired");
        $idToken = $signInResult->idToken();
        // Verify the ID token and get the user
        $verifiedIdToken = $this->auth->verifyIdToken($idToken);
        $uid = $verifiedIdToken->claims()->get('sub'); // 'sub' is the UID claim
        // Fetch user data from Firebase
        session(['_uid' => $uid]); //to keep the user signed in.will be check constantly elsewhere
        //$user = $this->auth->getUser($uid);
        return $this->check($uid);
        //return response()->json(['user' => $user]);
      } catch (\Kreait\Firebase\Exception\Auth\EmailExists $e) {
        $signInResult = $auth->signInWithEmailAndPassword($google_user["email"], "GoogleLoginNotRequired");
        $idToken = $signInResult->idToken();
        // Verify the ID token and get the user
        $verifiedIdToken = $this->auth->verifyIdToken($idToken);
        $uid = $verifiedIdToken->claims()->get('sub'); // 'sub' is the UID claim
        // Fetch user data from Firebase
        session(['_uid' => $uid]); //to keep the user signed in.will be check constantly elsewhere
        return $this->check($uid);
        //$user = $this->auth->getUser($uid);
        //return response()->json(['user' => $user]);
      }

    }catch(\Exception $e) {
      echo $e->getMessage();
    }
  }
  
  protected function check($uid) {
    //logic to check if email is verified should be here...
    
    $firebase = new FirebaseRealtimeDatabaseService;
    if ($firebase->checkReference('any_user/'.$uid)) {
      if ($firebase->getAnyUserData($uid)["filled"] == false) {
        echo "return offender!";
        return;
        //take them to fill their details
      } else {
        echo "issue hereeee";
        //takes them to home screen using their role
      }
    } else {
      echo "first timer!";
      $firebase->storeData('any_user/'.$uid, [
        'filled' => false,
        'role' => $request->route('role')
      ]);
      //refer to were it's to be filled
    }
  }
  protected function assignRole() {}
}
?>