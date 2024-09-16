<?php

namespace App\Models;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Prescription extends Model
{
  use HasFactory;
  protected $fillable = [
    'doctor_uid','patient_uid','prescription_body_json',
    'start_date','end_date','flagged','flagged_by'
    ];
  public function patient() {
    return $this->belongsTo(Patient::class, 'firebase_uid', 'patient_uid');
  }

  public function doctor() {
    return $this->belongsTo(User::class, 'firebase_uid', 'doctor_uid');
  }
}