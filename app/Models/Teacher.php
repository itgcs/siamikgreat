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
      'name',
      'place_birth',
      'religion',
      'date_birth',
      'home_address',
      'nationality',
      'nuptk',
      'gender',
      'created_at',
      'updated_at',
   ];

   public function grade()
   {
      return $this->hasOne(Grade::class, 'teacher_id');
   }
}