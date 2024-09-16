<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientsTableSeeder extends Seeder
{
    public function run()
    {
        Patient::create([
            'firebase_uid' => 'patient-uid-1',
            'status' => 'active',
            'card_serial' => 'CS001'
        ]);

        Patient::create([
            'firebase_uid' => 'patient-uid-2',
            'status' => 'active',
            'card_serial' => 'CS002'
        ]);
        Patient::create([
            'firebase_uid' => 'patient-uid-3',
            'status' => 'active',
            'card_serial' => 'CS003'
        ]);
    }
}