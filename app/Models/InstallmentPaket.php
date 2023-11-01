<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentPaket extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_id',
        'child_id',
        'created_at',
        'updated_at',
    ];
}
