<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'id' => 1,
                'name' => 'ishii',
                'email' => 'ishii.mamoru.1118@gmail.com',
                'password' => Hash::make('7ruzaxQRVDvjvxEnZwKS'),
                'created_at' => date('Y/m/d H:i:s'),
                'updated_at' => date('Y/m/d H:i:s'),
            ],
            [
                'id' => 2,
                'name' => 'test_user',
                'email' => 'example@example.com',
                'password' => Hash::make('KBhCcZH3irsh9QnX6aVm'),
                'created_at' => date('Y/m/d H:i:s'),
                'updated_at' => date('Y/m/d H:i:s'),
            ],
        ]);
    }
}
