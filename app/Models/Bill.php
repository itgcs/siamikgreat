<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

date_default_timezone_set('Asia/Jakarta');

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'student_id',
      'type',
      'subject',
      'amount',
      'paidOf',
      'discount',
      'deadline_invoice',
      'created_at',	
      'updated_at'	
    ]; 
    
   public function student()
   {
      return $this->belongsTo(Student::class, 'student_id');
   }
}