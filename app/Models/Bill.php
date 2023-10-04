<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'student_id	',
      'subject',
      'description',
      'amount',
      'created_at',	
      'updated_at'	
    ]; 
    
   public function student()
   {
      return $this->belongsTo(Student::class, 'student_id');
   }
}