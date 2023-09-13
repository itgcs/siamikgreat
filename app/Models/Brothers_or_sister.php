<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brothers_or_sister extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'name',
      'age',
      'grade',
      'created_at',
      'updated_at',
    ];

    public function student()
    {
       return $this->belongsToMany(Student::class, 'student_relations');
    }
    public function relationship()
    {
       return $this->belongsToMany(Relationship::class, 'student_relations');
    }
}