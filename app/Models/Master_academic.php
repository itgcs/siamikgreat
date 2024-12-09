<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master_academic extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'academic_year',
        'semester1',
        'end_semester1',
        'semester2',
        'end_semester2',
        'now_semester',
        'is_use',
        'created_at',
        'updated_at',
    ];
}
