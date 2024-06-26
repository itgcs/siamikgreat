<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score_attendance extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'grade_id',
      'teacher_id',
      'student_id',
      'score',
      'created_at',
      'updated_at',
    ];
}
