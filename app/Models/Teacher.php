<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'is_active',
      'unique_id',
      'name',
      'place_birth',
      'religion',
      'date_birth',
      'home_address',
      'nationality',
      'nuptk',
      'gender',
      'email',
      'handphone',
      'temporary_address',
      'last_education',
      'major',
      'created_at',
      'updated_at',
   ];

   public function grade()
   {
      return $this->belongsTo(Grade::class, 'teacher_id');
   }
}