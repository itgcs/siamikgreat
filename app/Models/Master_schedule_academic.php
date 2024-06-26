<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master_schedule_academic extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'date',
        'end_date',
        'created_at',
        'updated_at',
    ];
}
