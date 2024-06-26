<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subjects')->insert([
            [
                'name_subject' => 'english language',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'mandarin language',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'math',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'informatics',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'science',
                'created_at' => now(),
            ],
        ]);
    }
}
