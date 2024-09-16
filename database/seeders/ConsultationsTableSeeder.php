<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Consultation;

class ConsultationsTableSeeder extends Seeder
{
    public function run()
    {
        Consultation::create([
            'patient_uid' => 'patient-uid-1',
            'doctor_uid' => 'doctor-uid-1',
            'summary' => 'Initial consultation for fever and cough.'
        ]);
        Consultation::create([
            'patient_uid' => 'patient-uid-1',
            'doctor_uid' => 'doctor-uid-1',
            'summary' => 'Follow up consultation for fever and cough.'
        ]);
        Consultation::create([
            'patient_uid' => 'patient-uid-1',
            'doctor_uid' => 'doctor-uid-1',
            'summary' => 'Consultation for malaria .'
        ]);
        Consultation::create([
            'patient_uid' => 'patient-uid-1',
            'doctor_uid' => 'doctor-uid-1',
            'summary' => 'Final consultation for fever and cough.'
        ]);
    }
}