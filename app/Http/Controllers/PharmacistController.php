<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Models\Prescription;
use App\Http\Traits\FlagTrait;

class PharmacistController extends UserController
{
    use FlagTrait;
    
    public function __construct(){
      //check user controller to see what should be here....
    }
    
    
}
