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
                'name_subject' => 'Chinese',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Mathematics',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'English',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Science',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Bahasa Indonesia',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'PPKn',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Religion',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'IPS',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'IT',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Art and Craft',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Character Building',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'General Knowledge',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'PE',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Health Education',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Art and Design',
                'created_at' => now(),
            ],
        ]);
    }
}
