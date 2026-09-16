<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;

    protected $table = 'homework';

    public function getDocument()
    {
        if (!empty($this->document) && file_exists('uploads/homework/' . $this->document)) {
            return url('uploads/homework/' . $this->document);
        } else {
            return "";
        }
    }

    static public function getTotalHomework($class_id, $student_id)
    {
        return Homework::select('homework.id')
            ->join('class', 'class.id', 'homework.class_id')
            ->join('subject', 'subject.id', 'homework.subject_id')
            ->join('users', 'users.id', 'homework.created_by')
            ->where('homework.class_id', '=', $class_id)
            ->whereNotIn('homework.id', function ($query) use ($student_id) {
                $query->select('submit_homework.homework_id')
                    ->from('submit_homework')
                    ->where('submit_homework.student_id', '=', $student_id);
            })
            ->where('homework.is_delete', '=', 0)
            ->orderBy('homework.id', 'desc')->count();
    }

}
