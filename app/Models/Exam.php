<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'is_active',
        'semester',
        'name_exam',
        'type_exam',
        'date_exam',
        'materi',
        'teacher_id',
        'created_at',
        'updated_at',
    ];

    public function grade(){
        return $this->belongsToMany(Grade::class, 'grade_exams');
    }

    public function subject(){
        return $this->belongsToMany(Subject::class, 'subject_exams');
    }

    public function student(){
        return $this->belongsToMany(Student::class, 'student_exams');
    }

    public function score(){
        return $this->hasMany(Score::class);
    }
}
