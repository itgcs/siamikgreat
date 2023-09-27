<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;


    protected $fillable = [
      'id',
      'name',
      'teacher_id',
      'created_at',
      'updated_at',
    ];

    public function teacher()
    {
      $this->hasOne(Teacher::class, 'teacher_id');
    }

    public function student()
    {
      $this->hasMany(Student::class, 'grade_id');
    }
}