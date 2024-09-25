<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Exception\Auth\EmailExists;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Kreait\Firebase\Auth\Token\Exception\InvalidToken;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use App\Services\FirebaseRealtimeDatabaseService;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SessionController;

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
    //remove previous session
    SessionController::logout();

    $userProperties = [
      'email' => $request->input('email'),
      'emailVerified' => false,
      'password' => $request->input('password'),
      'displayName' => $request->input('name'),
      //change later
      'disabled' => false,
    ];

    try {
      $user = $this->auth->createUser($userProperties);
      if ($this->verifyEmail($request->input('email'))) {
        session(['emailVerified' => 'false']);
        echo "<script> alert('Verification link sent');</script> ";
        $auth = app('firebase')->createAuth();
        $signInResult = $auth->signInWithEmailAndPassword($request->input('email'), $request->input('password'));
        $idToken = $signInResult->idToken();
        // Verify the ID token and get the user
        $verifiedIdToken = $this->auth->verifyIdToken($idToken);
        $uid = $verifiedIdToken->claims()->get('sub'); // 'sub' is the UID claim
        //check if registration is being done by patient themselves or a authenticated practitioner 
        //if(session('registration_by_p')){
        $this->firstTimer($uid);
        //}
      }
    } catch (\Kreait\Firebase\Exception\Auth\EmailExists $e) {
      return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
  }

  // Login practitioner
  public function login(Request $request) {
    //remove previous session
    SessionController::logout();

    $email = $request->input('email');
    $password = $request->input('password');
    try {
      $auth = app('firebase')->createAuth();
      $signInResult = $auth->signInWithEmailAndPassword($email, $password);
      $idToken = $signInResult->idToken();
      // Verify the ID token and get the user
      $verifiedIdToken = $this->auth->verifyIdToken($idToken);
      $uid = $verifiedIdToken->claims()->get('sub'); // 'sub' is the UID claim
      return $this->check($uid);

    } catch (\Exception $e) {
      return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
  }


  protected function verifyEmail($email = null) {
    $user = $this->auth->getUserByEmail($email);
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


  public function redirectToGoogle(Request $request) {
    //remove previous session
    SessionController::logout();
    session(['role' => $request->route('role')]);
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback() {
    $user = Socialite::driver('google')->user();
  }

  public function GoogleLogin(Request $request) {
    if (!session()->has('role')) {
      session(['role' => $request->route('role')]);
    }
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
        $this->firstTimer($uid, true);
        $this->check($uid, true);
      } catch (\Kreait\Firebase\Exception\Auth\EmailExists $e) {
        //this catch block means that the google account already is registered with firebase
        $signInResult = $auth->signInWithEmailAndPassword($google_user["email"], "GoogleLoginNotRequired");
        $idToken = $signInResult->idToken();
        // Verify the ID token and get the user
        $verifiedIdToken = $this->auth->verifyIdToken($idToken);
        $uid = $verifiedIdToken->claims()->get('sub'); // 'sub' is the UID claim
        //confirms that the email is verified and vital information is filled if not it tells them to either go verify or fill.
        $this->check($uid, true);

      }

    }catch(\Exception $e) {
      echo $e->getMessage();
    }
  }


  //check() is called during login
  //checks if user/patient has verified their email or filled in complete data
  protected function check($uid, $bypass = false) {
    //logic to check if email is verified should be here...

    //check if user or patient has filled necessarily details
    $firebase = new FirebaseRealtimeDatabaseService;
    if ($firebase->checkReference('any_user/'.$uid)) {
      if ($firebase->getAnyUserData('any_user/'.$uid)["filled"] == false) {
        echo "return offender!";
        return;
        //take them to fill their details
      } else {
        SessionController::login($uid);
        //takes them to home screen using their role
      }
    }
  }

  //only firstTimer calls firstTimerHelper
  protected function firstTimer($uid, $redirect = false) {
    $firebase = new FirebaseRealtimeDatabaseService;
    $firebase->storeData('any_user/'.$uid, [
      'filled' => false,
      'role' => session('role') //setting the role here is crucial
    ]);
    //save to laravel Database
    $this->firstTimerHelper($uid);
    //invalidate the role session
    session()->forget('role');
    if (!$redirect) {
      //return redirect('/login');
    }else if($redirect){
      //go to dashboard
    }
  }

  protected function firstTimerHelper($uid) {
    if (session('role') == 'patient') {
      Patient::create([
        'firebase_uid' => $uid,
        'status' => 'active'
      ]);
      try {
        $Permission = new PermissionController;
        $Permission->setPatientRole($uid);
      }catch(Exception $e) {}

    } else if (session('role') == 'doctor' || session('role') == 'pharmacist') {
      User::create([
        'firebase_uid' => $uid,
        'status' => 'active'
      ]);
    }
  }
}
?>