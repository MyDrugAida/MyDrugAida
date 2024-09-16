<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prescription;

class PrescriptionsTableSeeder extends Seeder
{
    public function run()
    {
        Prescription::create([
            'patient_uid' => 'patient-uid-1',
            'doctor_uid' => 'doctor-uid-2',
            'prescription_body_json' => json_encode([
                'medication' => 'Paracetamol',
                'dosage' => '500mg',
                'frequency' => 'Twice a day'
            ]),
            'start_date' => now(),
            'end_date' => now()->addDays(5),
            'flagged' => false,
            'flagged_by' => ''
        ]);
        Prescription::create([
            'patient_uid' => 'patient-uid-1',
            'doctor_uid' => 'doctor-uid-1',
            'prescription_body_json' => json_encode([
                'medication' => 'Ibuprofen',
                'dosage' => '200mg',
                'frequency' => 'Twice a day'
            ]),
            'start_date' => now(),
            'end_date' => now()->addDays(5),
            'flagged' => false,
            'flagged_by' => ''
        ]);
        Prescription::create([
            'patient_uid' => 'patient-uid-1',
            'doctor_uid' => 'doctor-uid-1',
            'prescription_body_json' => json_encode([
                'medication' => 'Ibuprofen',
                'dosage' => '200mg',
                'frequency' => 'Twice a day'
            ]),
            'start_date' => now(),
            'end_date' => now()->addDays(5),
            'flagged' => false,
            'flagged_by' => ''
        ]);
        Prescription::create([
            'patient_uid' => 'patient-uid-1',
            'doctor_uid' => 'doctor-uid-1',
            'prescription_body_json' => json_encode([
                'medication' => 'Diabetmin',
                'dosage' => '500mg',
                'frequency' => 'Twice a day'
            ]),
            'start_date' => now(),
            'end_date' => now()->addDays(5),
            'flagged' => false,
            'flagged_by' => ''
        ]);
    }
}