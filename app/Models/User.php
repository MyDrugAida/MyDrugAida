<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Report;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
  use HasFactory,
  Notifiable,
  HasRoles;
  protected $primaryKey = 'firebase_uid';
  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'firebase_uid',
    'role',
    'status'
  ];

  /**
  * The attributes that should be hidden for serialization.
  *
  * @var array<int, string>
  */
  protected $hidden = [
  ];

  /**
  * Get the attributes that should be cast.
  *
  * @return array<string, string>
  */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  public function allPrescriptions() {
    return $this->hasMany(Prescription::class, 'doctor_uid', 'firebase_uid');
  }

  public function singlePatientPrescriptions() {
    //if (session()->has('patient_uid')) {
      $patient_uid = request()->route('patient_uid');
      //return $this->hasMany(Prescription::class, 'doctor_uid', 'firebase_uid');
      return $this->hasMany(Prescription::class, 'doctor_uid', 'firebase_uid')->where('patient_uid', $patient_uid);
    //}
  }

  public function allReports() {
    return $this->hasMany(Report::class, 'user_uid', 'firebase_uid');
  }
  
  public function singlePatientReports(){
    
  }
}