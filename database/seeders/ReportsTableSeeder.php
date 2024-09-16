<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Report;

class ReportsTableSeeder extends Seeder
{
    public function run()
    {
        Report::create([
            'patient_uid' => 'patient-uid-1',
            'user_uid' => 'doctor-uid-1',
            'report' => 'The patient showed improvement after 3 days of medication.'
        ]);
        Report::create([
            'patient_uid' => 'patient-uid-1',
            'user_uid' => 'doctor-uid-1',
            'report' => 'The patient showed no  improvement after 3 days of medication.'
        ]);
        Report::create([
            'patient_uid' => 'patient-uid-1',
            'user_uid' => 'doctor-uid-1',
            'report' => 'The patient showed some  improvement after 3 days of medication.'
        ]);
    }
}