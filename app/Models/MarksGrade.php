<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarksGrade extends Model
{
    use HasFactory;

    protected $table = 'marks_grade';

    static public function getGrade($percentage)
    {
        $return = MarksGrade::select('marks_grade.*')
            ->where('percent_from', '<=', $percentage)
            ->where('percent_to', '>=', $percentage)->first();

        return $return = !empty($return->name) ? $return->name : '';
    }
}
