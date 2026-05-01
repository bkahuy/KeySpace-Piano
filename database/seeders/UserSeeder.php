<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo tài khoản Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Bùi Khắc Huy',
                'password' => Hash::make('1'), // Mật khẩu là: password
                'phone' => '0837607568',
                'address' => 'Thanh Hóa, Việt Nam',
                'role' => 'admin',
            ]
        );

        // Tạo tài khoản Khách hàng test
        User::updateOrCreate(
            ['email' => 'khachhang@gmail.com'],
            [
                'name' => 'Nguyễn Thành Đồng',
                'password' => Hash::make('1'),
                'phone' => '0123456789',
                'address' => 'TP.Hải Dương, Việt Nam',
                'role' => 'customer',
            ]
        );
    }
}
