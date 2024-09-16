<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GeneticInfo;

class GeneticInfosTableSeeder extends Seeder
{
    public function run()
    {
        GeneticInfo::create([
            'patient_uid' => 'patient-uid-1',
            'genetic_summary' => 'No known genetic conditions.'
        ]);
    }
}
