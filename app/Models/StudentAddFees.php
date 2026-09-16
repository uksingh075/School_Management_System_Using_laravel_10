<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAddFees extends Model
{
    use HasFactory;

    protected $table = 'student_add_fees';

    public static function getTotalTodayFees()
    {
        return self::where('student_add_fees.is_payment', '=', 1)
            ->whereDate('student_add_fees.created_at', '=', date('Y-m-d'))
            ->sum('student_add_fees.paid_fee_amount');
    }

    public static function getTotalFees()
    {
        return self::where('student_add_fees.is_payment', '=', 1)
            ->sum('student_add_fees.paid_fee_amount');
    }

    public static function TotalFeesPaidAmount($student_id)
    {
        return self::where('student_add_fees.is_payment', '=', 1)
            ->where('student_add_fees.student_id', '=', $student_id)
            ->sum('student_add_fees.paid_fee_amount');
    }

    public static function TotalFeesPaidAmountParent($student_ids)
    {
        return self::where('student_add_fees.is_payment', '=', 1)
            ->whereIn('student_add_fees.student_id', $student_ids)
            ->sum('student_add_fees.paid_fee_amount');
    }
}
