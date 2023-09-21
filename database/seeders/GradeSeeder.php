<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
   {
      DB::table('grades')->insert([
         [
            'name' => 'Junior High School - A',
            'teacher_id' => 1,
            'created_at' => date("Y-m-d"),
         ],
         [
            'name' => 'Junior High School - B',
            'teacher_id' => 2,
            'created_at' => date("Y-m-d"),
         ],
         [
            'name' => 'Junior High School - C',
            'teacher_id' => 3,
            'created_at' => date("Y-m-d"),
         ],
      ]);
   }
}