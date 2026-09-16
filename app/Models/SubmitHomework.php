<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmitHomework extends Model
{
    use HasFactory;

    protected $table = 'submit_homework';

    public function getDocument()
    {
        if (!empty($this->document) && file_exists('uploads/homework/' . $this->document)) {
            return url('uploads/homework/' . $this->document);
        } else {
            return "";
        }
    }

    public function getHomework()
    {
        return $this->belongsTo(Homework::class, "homework_id");
    }

    public function getStudent()
    {
        return $this->belongsTo(User::class, "student_id");
    }

    static public function getTotalSubmittedHomework($student_id)
    {
        return SubmitHomework::select('submit_homework.id')
            ->join('homework', 'homework.id', 'submit_homework.homework_id')
            ->join('class', 'class.id', 'homework.class_id')
            ->join('subject', 'subject.id', 'homework.subject_id')
            ->where('submit_homework.student_id', '=', $student_id)
            ->orderBy('submit_homework.id', 'desc')->count();
    }

    static public function getTotalSubmittedHomeworkParent($student_ids)
    {
        return SubmitHomework::select('submit_homework.id')
            ->join('homework', 'homework.id', 'submit_homework.homework_id')
            ->join('class', 'class.id', 'homework.class_id')
            ->join('subject', 'subject.id', 'homework.subject_id')
            ->whereIn('submit_homework.student_id', $student_ids)
            ->orderBy('submit_homework.id', 'desc')->count();
    }
}
