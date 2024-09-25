<?php
namespace App\Http\Traits;

use App\Models\Prescription;

trait FlagTrait
{
  protected function flagPrescription() {
    try {
      $id = request()->route('id');
      $prescription = Prescription::where('id', $id)->first();
      $flaggers = json_decode($prescription->getAttribute('flagged_by'));
      
      if (empty($flaggers)) {
        
      }
    }catch(Exception $e) {}
  }
  
  protected function unflagPrescription() {
    try {
      $id = request()->route('id');
      $prescription = Prescription::where('id', $id)->first();
      $flaggers = json_decode($prescription->getAttribute('flagged_by'));
      
      if (empty($flaggers)) {
        
      }
    }catch(Exception $e) {}
  }

  protected function getFlaggedBy() {
    try {
      $id = request()->route('id');
      $prescription = Prescription::where('id', $id)->first();
      $flaggers = $prescription->getAttribute('flagged_by');
      return $flaggers;
    }catch(Exception $e) {}
  }
}