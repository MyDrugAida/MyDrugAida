<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'firebase_uid' => 'doctor-uid-1',
            'role' => 'doctor',
            'status' => 'active'
        ]);
        User::create([
            'firebase_uid' => 'doctor-uid-2',
            'role' => 'doctor',
            'status' => 'active'
        ]);
        User::create([
            'firebase_uid' => 'pharmacist-uid-1',
            'role' => 'pharmacist',
            'status' => 'active'
        ]);
        User::create([
            'firebase_uid' => 'pharmacist-uid-2',
            'role' => 'pharmacist',
            'status' => 'active'
        ]);
    }
}
