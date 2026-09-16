<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subject';

    public static function getTotalSubject()
    {
        return self::select('subject.id')
            ->where('subject.is_delete', '=', 0)
            ->where('subject.status', '=', 0)
            ->count();
    }
}
