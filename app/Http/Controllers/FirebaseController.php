<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
//use Kreait\Firebase\Auth as FirebaseAuth;



class FirebaseController extends Controller
{

  protected $firebaseAuth;
  public function __construct(FirebaseAuth $firebaseAuth) {
    $this->firebaseAuth = $firebaseAuth;
  }

  // Signup

  public function register(Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');
    try {
      $user = $this->firebaseAuth->createUserWithEmailAndPassword($email, $password);
      return 'User created: '. $user->uid;
    } catch (\Exception $e) {
      return 'Error: ' . $e->getMessage();
    }

  }

  // Login

  public function login(Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');
    try {
      $signInResult = $this->firebaseAuth->signInWithEmailAndPassword($email, $password);
      return 'Successfully signed in: ' . $signInResult->data()['idToken'];
    } catch (\Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  public function logout() {
    // Clear the authentication token on the client side
    // Example: echo '<script>localStorage.removeItem("firebaseAuthToken");</script>';
    return 'Logout successful';
  }

  public function realtimedatabase() {
    $firebase = app('firebase');
    $realtimeDatabase = $firebase->createDatabase();
    $reference = $realtimeDatabase->getReference('users');
    $user = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
    ];
    $reference->push($user);


  }

}