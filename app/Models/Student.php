<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // لتحديد الأعمدة المسموح بحفظها (mass assignment)
    protected $fillable = [
        'name',
        'birth_date',
    ];

    // التحويل التلقائي للحقل لتاريخ باستخدام كاربون
    protected $casts = [
        'birth_date' => 'date',
    ];
}
