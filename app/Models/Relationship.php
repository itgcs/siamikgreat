<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'name',
      'place_birth',
      'date_birth',
      'occupation',
      'company_name',
      'company_address',
      'home_address',
      'telephone',
      'mobilephone',
      'id_or_passport',
      'nationality',
      'phone',
      'email',
      'created_at',
      'updated_at',
    ];



   public function student()
      {
         return $this->belongsToMany(Student::class, 'student_relations');
      }
   public function brotherOrSister()
      {
         return $this->belongsToMany(Brothers_or_sister::class, 'student_relations');
      }
}