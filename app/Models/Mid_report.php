<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mid_report extends Model
{
    use HasFactory;
    
    protected $fillable =[
        'id',
        'student_id',
        'grade_id',
        'class_teacher_id',
        'remarks',
        'semester',
        'created_at',
        'updated_at',
    ];
}
