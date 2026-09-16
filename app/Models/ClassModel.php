<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'class';

    public static function getTotalClass()
    {
        return self::select('class.id')
            ->where('class.is_delete', '=', 0)
            ->where('class.status', '=', 0)
            ->count();
    }
}
