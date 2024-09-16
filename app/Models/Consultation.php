<?php

namespace App\Models;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
  use HasFactory;
  public function patient() {
    return $this->belongsTo(Patient::class, 'patient_uid', 'firebase_uid');
  }

  public function doctor() {
    return $this->belongsTo(User::class, 'doctor_uid', 'firebase_uid');
  }
}