<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    public static function getTotalExam()
    {
        return self::select('exams.id')
            ->where('exams.is_delete', '=', 0)
            ->count();
    }
}
