<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      // $table->string('unique_id')->unique();
      // $table->string('name');
      // $table->string('nik')->unique();
      // $table->string('religion');
      // $table->string('gender');
      // $table->string('place_birth');
      // $table->string('nationality');
      // $table->date('date_birth');
      // $table->text('home_address');
      // $table->text('temporary_address');
      // $table->string('handphone');
      // $table->string('email');
      // $table->string('last_education');
      // $table->string('major');


      DB::table('teachers')->insert([
         [
            'is_active' => 1,
            'user_id' => 4,
            'unique_id' => date('Ym') . '0001',
            'name' => 'Awan Santoso',
            'nik' => '1234567890123456',
            'religion' => 'Islam',
            'gender' => 'Male',
            'nationality' => 'Indonesia',
            'place_birth' => 'Surabaya',
            'date_birth' => '1978-04-12',
            'home_address' => 'Desa paku, Kecamatan Menganti, Kabupaten Gresik',
            'temporary_address' => 'Desa paku, Kecamatan Menganti, Kabupaten Gresik',
            'handphone' => '082141311214',
            'email' => 'dummy1@email.com',
            'last_education' => 'bachelor degree',
            'major' => 'Informatics',
            'created_at' => date("Y-m-d"),
         ],
      ]);
    }
}