<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@iriv.ma'],
            [
                'name'     => 'Admin IRIV',
                'email'    => 'admin@iriv.ma',
                'password' => Hash::make('password'),
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'form_status'],
            ['value' => 'open']
        );
    }
}
