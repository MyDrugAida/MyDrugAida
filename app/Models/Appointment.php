<?php

namespace App\Models;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    public function patient()
    {
        return $this->belongsTo(Patient::class,'firebase_uid','patient_uid');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class,'firebase_uid','user_uid');
    }
}