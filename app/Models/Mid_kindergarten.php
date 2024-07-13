<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mid_kindergarten extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'student_id',
        'grade_id',
        'class_teacher_id',
        'english_language',
        'mandarin_language',
        'writing_skill',
        'reading_skill',
        'phonic',
        'science',
        'art_and_craft',
        'physical_education',
        'able_to_sit_quietly',
        'willingness_to_listen',
        'willingness_to_work',
        'willingness_to_sing',
        'remarks',
        'semester',
        'created_at',
        'updated_at',
    ];
}
