<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignClassTeacher extends Model
{
    use HasFactory;

    protected $table = 'assign_class_teacher';

    static public function getMyTimetable($class_id, $subject_id)
    {
        $getWeek = Week::getWeekUsingName(date('l'));

        return ClassSubjectTimetable::where('class_id', '=', $class_id)
            ->where('subject_id', '=', $subject_id)
            ->where('week_id', '=', $getWeek->id)->first();
    }

    public static function getTotalClass($teacher_id)
    {
        return AssignClassTeacher::select('assign_class_teacher.id')
            ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
            ->where('assign_class_teacher.is_delete', '=', 0)
            ->where('assign_class_teacher.status', '=', 0)
            ->where('assign_class_teacher.teacher_id', '=', $teacher_id)
            ->groupBy('assign_class_teacher.class_id')
            ->count();
    }

    public static function getTotalSubject($teacher_id)
    {
        return AssignClassTeacher::select('assign_class_teacher.id')
            ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
            ->join('class_subject', 'class_subject.class_id', '=', 'class.id')
            ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
            ->where('assign_class_teacher.is_delete', '=', 0)
            ->where('assign_class_teacher.status', '=', 0)
            ->where('assign_class_teacher.teacher_id', '=', $teacher_id)
            ->where('subject.is_delete', '=', 0)
            ->where('subject.status', '=', 0)
            ->where('class_subject.is_delete', '=', 0)
            ->where('class_subject.status', '=', 0)
            ->count();
    }
}
