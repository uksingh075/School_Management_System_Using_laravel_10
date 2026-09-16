<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function getTotalStudent($teacher_id)
    {
        return User::select('users.id')
            ->join('class', 'class.id', '=', 'users.class_id')
            ->join('assign_class_teacher', 'assign_class_teacher.class_id', '=', 'class.id')
            ->where('assign_class_teacher.teacher_id', '=', $teacher_id)
            ->where('assign_class_teacher.status', '=', 0)
            ->where('assign_class_teacher.is_delete', '=', 0)
            ->where('users.user_type', '=', 3)
            ->where('users.is_delete', '=', 0)
            ->where('users.status', '=', 0)
            ->count();
    }

    public static function getTotalUser($user_type)
    {
        return self::select('users.id')
            ->where('user_type', '=', $user_type)
            ->where('is_delete', '=', 0)
            ->count();
    }

    public function getProfile()
    {
        if (!empty($this->profile_pic) && file_exists('uploads/profile/' . $this->profile_pic)) {
            return url('uploads/profile/' . $this->profile_pic);
        } else {
            return url('uploads/profile/user.jpg');
        }
    }

    public function getPaidFeeAmount($student_id, $class_id)
    {
        return StudentAddFees::where('student_add_fees.class_id', '=', $class_id)
            ->where('student_add_fees.student_id', '=', $student_id)
            ->sum('student_add_fees.paid_fee_amount');
    }

    static public function getTotalStudentCount($parent_id)
    {
        return User::select('users.id')
            ->join('users as parent', 'parent.id', '=', 'users.parent_id')
            ->join('class', 'class.id', '=', 'users.class_id', 'left')
            ->where('users.user_type', '=', 3)
            ->where('users.parent_id', '=', $parent_id)
            ->where('users.is_delete', '=', 0)
            ->count();
    }

    static public function getMyStudentIds($parent_id)
    {
        $return = User::select('users.id')
            ->join('users as parent', 'parent.id', '=', 'users.parent_id')
            ->join('class', 'class.id', '=', 'users.class_id', 'left')
            ->where('users.user_type', '=', 3)
            ->where('users.parent_id', '=', $parent_id)
            ->where('users.is_delete', '=', 0)
            ->get();

        $student_ids = array();
        foreach ($return as $value) {
            $student_ids[] = $value->id;
        }

        return $student_ids;
    }
}
