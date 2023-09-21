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
        //$table->string('name');
      //   $table->string('nuptk')->unique()->nullable();
      //   $table->string('religion');
      //   $table->string('gender');
      //   $table->string('nationality');
      //   $table->string('place_birth');
      //   $table->string('date_birth');
      //   $table->string('home_address');


      DB::table('teachers')->insert([
         [
           'name' => 'Awan Santoso',
            'nuptk' => '1234567890123456',
            'religion' => 'Islam',
            'gender' => 'Male',
            'nationality' => 'Indonesia',
            'place_birth' => 'Surabaya',
            'date_birth' => '1978-04-12',
            'home_address' => 'Desa paku, Kecamatan Menganti, Kabupaten Gresik',
            'created_at' => date("Y-m-d"),
         ],
         [
           'name' => 'Yuli Astuti',
            'nuptk' => '1234567890123455',
            'religion' => 'Islam',
            'gender' => 'Female',
            'nationality' => 'Indonesia',
            'place_birth' => 'Surabaya',
            'date_birth' => '1978-04-12',
            'home_address' => 'Desa paku, Kecamatan Menganti, Kabupaten Gresik',
            'created_at' => date("Y-m-d"),
         ],
         [
           'name' => 'Kurniawan',
            'nuptk' => '1234567890123454',
            'religion' => 'Islam',
            'gender' => 'Male',
            'nationality' => 'Indonesia',
            'place_birth' => 'Surabaya',
            'date_birth' => '1978-04-12',
            'home_address' => 'Desa paku, Kecamatan Menganti, Kabupaten Gresik',
            'created_at' => date("Y-m-d"),
         ],
      ]);
    }
}