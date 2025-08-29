<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
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
            'pin' => '1234',
            'status' => 'aktif',
        ]);
    }
}
