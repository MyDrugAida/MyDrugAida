<?php

namespace App\Models;
use App\Models\Consultation;
use App\Models\Prescription;
use App\Models\Appointment;
use App\Models\GeneticInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Patient extends Authenticatable
{
  use HasFactory;
  use HasRoles;

  protected $primaryKey = 'firebase_uid';
  protected $keyType = 'string';
  public $incrementing = false;
  
  protected $fillable = [
    'firebase_uid',
    'status'
  ];
  
  public function prescriptions() {
    return $this->hasMany(Prescription::class, 'patient_uid', 'firebase_uid');
  }

  public function consultations() {
    return $this->hasMany(Consultation::class, 'patient_uid', 'firebase_uid');
  }

  public function appointments() {
    return $this->hasMany(Appointment::class, 'patient_uid', 'firebase_uid');
  }
  public function geneticInfos() {
    return $this->hasOne(GeneticInfo::class, 'patient_uid', 'firebase_uid');
  }
}