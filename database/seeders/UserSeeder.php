<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

      $data = [
         [
            'username' => 'superadmin',
            'password' => Hash::make('superadmin'),
            'role_id' => '1',
            'created_at' => now(),
         ],
         [
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'role_id' => '2',
            'created_at' => now(),
         ],
         [
            'username' => 'teacher',
            'password' => Hash::make('teacher'),
            'role_id' => '3',
            'created_at' => now(),
         ],
         [
            'username' => 'student',
            'password' => Hash::make('student'),
            'role_id' => '4',
            'created_at' => now(),
         ],
         [
            'username' => 'parent',
            'password' => Hash::make('parent'),
            'role_id' => '5',
            'created_at' => now(),
         ],
      ];

      DB::table('users')->insert($data);
   }
}