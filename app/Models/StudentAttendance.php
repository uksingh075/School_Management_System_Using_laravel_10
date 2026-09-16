<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $table = 'student_attendance';

    static public function getTotalAttendance($student_id)
    {
        return StudentAttendance::select('student_attendance.*', 'class.name as class_name')
            ->join('class', 'class.id', '=', 'student_attendance.class_id')
            ->where('student_attendance.student_id', '=',$student_id)
            ->orderBy('student_attendance.id', 'desc')->count();
    }

    static public function getTotalAttendanceParent($student_ids)
    {
        return StudentAttendance::select('student_attendance.id')
            ->join('class', 'class.id', '=', 'student_attendance.class_id')
            ->whereIn('student_attendance.student_id', $student_ids)
            ->orderBy('student_attendance.id', 'desc')->count();
    }
}
