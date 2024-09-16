<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;

class AppointmentsTableSeeder extends Seeder
{
    public function run()
    {
        Appointment::create([
            'patient_uid' => 'patient-uid-1',
            'user_uid' => 'doctor-uid-1',
            'appointment_date' => now()->addDays(20),
            'visibility_to_patient' => true,
            'appointment_done' => false
        ]);
        Appointment::create([
            'patient_uid' => 'patient-uid-1',
            'user_uid' => 'doctor-uid-1',
            'appointment_date' => now()->addDays(20),
            'visibility_to_patient' => true,
            'appointment_done' => false
        ]);
        Appointment::create([
            'patient_uid' => 'patient-uid-2',
            'user_uid' => 'pharmacist-uid-1',
            'appointment_date' => now()->addDays(20),
            'visibility_to_patient' => true,
            'appointment_done' => false
        ]);
        Appointment::create([
            'patient_uid' => 'patient-uid-1',
            'user_uid' => 'pharmacist-uid-1',
            'appointment_date' => now()->addDays(20),
            'visibility_to_patient' => true,
            'appointment_done' => false
        ]);
        Appointment::create([
            'patient_uid' => 'patient-uid-2',
            'user_uid' => 'doctor-uid-1',
            'appointment_date' => now()->addDays(20),
            'visibility_to_patient' => true,
            'appointment_done' => false
        ]);
        Appointment::create([
            'patient_uid' => 'patient-uid-3',
            'user_uid' => 'doctor-uid-1',
            'appointment_date' => now()->addDays(20),
            'visibility_to_patient' => true,
            'appointment_done' => false
        ]);
    }
}
