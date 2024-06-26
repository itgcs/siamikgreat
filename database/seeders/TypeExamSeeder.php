<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_exams')->insert([
            [
                'name' => 'Ulangan Harian',
                'created_at' => now(),
            ],
            [
                'name' => 'UTS',
                'created_at' => now(),
            ],
            [
                'name' => 'UAS',
                'created_at' => now(),
            ],
            [
                'name' => 'Praktikum',
                'created_at' => now(),
            ],
        ]);
    }
}
