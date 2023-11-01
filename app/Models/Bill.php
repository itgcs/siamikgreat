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
      'dp',
      'paidOf',
      'discount',
      'deadline_invoice',
      'date_change_bill',
      'installment',
      'amount_installment',
      'created_at',	
      'updated_at'	
    ]; 
    
   public function student()
   {
      return $this->belongsTo(Student::class, 'student_id');
   }


   public function bill_collection()
   {
      return $this->hasMany(BillCollection::class, 'bill_id');
   }

   public function bill_installments()
   {
      return $this->belongsToMany(Bill::class, 'installment_pakets', 'main_id', 'child_id');
   }
}