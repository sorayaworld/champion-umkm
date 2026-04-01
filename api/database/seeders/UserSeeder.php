<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('fafa2222');

        User::create([
            'name'     => 'user',
            'email'    => 'user@mail.com',
            'password' => $defaultPassword,
            'nik'      => '1234567890123456',
            'phone'    => '081234567',
            'role'     => 'borrower',
        ]);

        User::create([
            'name'     => 'Officer',
            'email'    => 'officer@mail.com',
            'password' => $defaultPassword,
            'nik'      => '1234567890123456',
            'phone'    => '081234567',
            'role'     => 'officer',
        ]);

        User::create([
            'name'     => 'Manager',
            'email'    => 'manager@mail.com',
            'password' => $defaultPassword,
            'nik'      => '1234567890123456',
            'phone'    => '081234567',
            'role'     => 'manager',
        ]);

        User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@mail.com',
            'password' => $defaultPassword,
            'nik'      => '1234567890123456',
            'phone'    => '081234567',
            'role'     => 'admin',
        ]);
    }
}