<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Prescription;
use App\Models\User;

class UserController extends Controller
{  
  protected $practitioner;
  //authentication is carried out in the child class (DoctorController and PharmacistController)
  public function __construct() {
    //this code in the construct should be transferred to the child controllers,along with authentication
    //$uid should be gotten from session
    $uid = 'uKzUSMRzk8Yn6knx5Gr5L025H4G2';
    $this->practitioner = User::where('firebase_uid', $uid)->first();
  }

  public function loadPrescriptions() {
  
    return $this->practitioner->allPrescriptions;
  }
  
  public function loadPrescriptionForAPatient(){
    //this hard coded parameter passed here should come from a session variable that's destroyed right after
    return $this->practitioner->singlePatientPrescriptions;
  }
  
  


}