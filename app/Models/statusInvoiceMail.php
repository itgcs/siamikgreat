<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class statusInvoiceMail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'bill_id',
        'status',
        'charge',
        'past_due',
        'created_at',
    ];


    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }
}
