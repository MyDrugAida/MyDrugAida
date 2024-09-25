<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
  
  public function __construct(){
    
  }
  //maybe the User is a doctor or patient
  protected function distinguishUserRole(Request $request) {
    $uid = $request->input('uid');
    $role = $request->input('role');
    if ($role == 'doctor' || $role == 'pharmacist') {
      //$user = User::where('firebase_uid', $uid)->first();
      $user = User::where('firebase_uid', $uid)->first();
      if ($user) {
        try{
        //assign the role to theexist using Spatie        $user = User::where('firebase_uid', $uid)->first();
        $user->assignRole($role); // Assign role to the user
        
        //update in the laravel database
        $user->update(['role' => $role]);
        } catch (Exception $e){
          return "Failed to enact changes.Error: ".$e->getMessage();
        }
      } else {
        return "User doesn't exist";
      }
    }
  }
  
  protected function setPatientRole($uid){
    //$uid = $request->input('uid');
    $patient = Patient::where('firebase_uid', $uid)->first();
      if ($patient) {
        try{
        //assign the role to theexist using Spatie        $user = User::where('firebase_uid', $uid)->first();
        $patient->assignRole('patient'); // Assign role to the user
        
        } catch (Exception $e){
          return "Failed to enact changes.Error: ".$e->getMessage();
        }
      } else {
        return "Patient doesn't exist";
      }
  }
}