<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');  // Menggunakan Faker untuk Indonesia

        // Membuat 1 user dengan role 'admin'
        User::create([
            'id' => (string) Str::uuid(),
            'username' => 'adminneo',
            'email' => 'adminneo@gmail.com',
            'password' => Hash::make('admin123'),
            'first_name' => 'Admin',
            'last_name' => 'Neo',
            'phone_1' => '08123456789',
            'phone_2' => null,
            'role' => 'admin',
            'pin' => $faker->numerify('####'),
            'status' => 'aktif',
        ]);

        // Membuat 9 user dengan role 'member' secara acak menggunakan Faker
        foreach (range(1, 9) as $i) {
            User::create([
                'id' => (string) Str::uuid(),
                'username' => $faker->userName,  // Username acak sesuai dengan format Indonesia
                'email' => $faker->unique()->safeEmail,  // Email acak sesuai dengan format Indonesia
                'password' => Hash::make('password' . $i),  // Password dengan penambahan angka untuk variasi
                'first_name' => $faker->firstName,  // Nama depan acak
                'last_name' => $faker->lastName,  // Nama belakang acak
                'phone_1' => $faker->phoneNumber,  // Nomor telepon acak sesuai format Indonesia
                'phone_2' => $faker->optional()->phoneNumber,  // Nomor telepon kedua (opsional)
                'role' => 'member',
                'pin' => $faker->numerify('####'),
                'status' => $faker->randomElement(['aktif', 'tidak aktif', 'suspend']),  // Status acak antara 'aktif', 'tidak aktif', 'suspend'
            ]);
        }
    }
}
