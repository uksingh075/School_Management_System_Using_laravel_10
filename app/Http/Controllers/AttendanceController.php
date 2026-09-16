<?php

namespace App\Http\Controllers;

use App\Models\AssignClassTeacher;
use App\Models\ClassModel;
use App\Models\StudentAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function StudentAttendance(Request $request)
    {
        $data['getClass'] = ClassModel::select('class.*')
            ->join('users', 'users.id', 'class.created_by')
            ->where('class.is_delete', '=', 0)
            ->where('class.status', '=', 0)->orderBy('class.name', 'asc')->get();

        if (!empty($request->get('class_id')) && $request->get('attendance_date')) {

            $data['getStudent'] = User::select('users.id', 'users.name', 'users.last_name')
                ->where('users.user_type', '=', 3)
                ->where('users.is_delete', '=', 0)
                ->where('users.status', '=', 0)
                ->where('users.class_id', '=', $request->get('class_id'))->orderBy('users.id', 'desc')->get();
        }

        $data['header_title'] = 'Student Attendance';
        return view('admin.attendance.student', $data);
    }

    public function StudentAttendanceTeacher(Request $request)
    {
        $data['getClass'] = AssignClassTeacher::select('assign_class_teacher.*', 'class.name as class_name', 'class.id as class_id',)
            ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
            ->where('assign_class_teacher.is_delete', '=', 0)
            ->where('assign_class_teacher.status', '=', 0)
            ->where('assign_class_teacher.teacher_id', '=', Auth::user()->id)
            ->groupBy('assign_class_teacher.class_id')->get();


        if (!empty($request->get('class_id')) && $request->get('attendance_date')) {

            $data['getStudent'] = User::select('users.id', 'users.name', 'users.last_name')
                ->where('users.user_type', '=', 3)
                ->where('users.is_delete', '=', 0)
                ->where('users.status', '=', 0)
                ->where('users.class_id', '=', $request->get('class_id'))->orderBy('users.id', 'desc')->get();
        }

        $data['header_title'] = 'Student Attendance';
        return view('teacher.attendance.student', $data);
    }

    public function StudentAttendanceSubmit(Request $request)
    {
        $check_attendance = StudentAttendance::where('student_id', '=', $request->student_id)
            ->where('class_id', '=', $request->class_id)
            ->where('attendance_date', '=', $request->attendance_date)->first();

        if (!empty($check_attendance)) {
            $attendance = $check_attendance;
        } else {
            $attendance = new StudentAttendance;
            $attendance->class_id = $request->class_id;
            $attendance->attendance_date = $request->attendance_date;
            $attendance->student_id = $request->student_id;
            $attendance->created_by = Auth::user()->id;
        }

        $attendance->attendance_type = $request->attendance_type;
        $attendance->save();

        $json['message'] = "Attendance Saved Successfully";

        echo json_encode($json);
    }

    public function AttendanceReport(Request $request)
    {
        $data['getClass'] = ClassModel::select('class.*')
            ->join('users', 'users.id', 'class.created_by')
            ->where('class.is_delete', '=', 0)
            ->where('class.status', '=', 0)->orderBy('class.name', 'asc')->get();

        $return = StudentAttendance::select('student_attendance.*', 'class.name as class_name', 'student.name as student_name', 'student.last_name as student_last_name', 'createdby.name as createdby_name')
            ->join('class', 'class.id', '=', 'student_attendance.class_id')
            ->join('users as student', 'student.id', '=', 'student_attendance.student_id')
            ->join('users as createdby', 'createdby.id', '=', 'student_attendance.created_by');

        if (!empty($request->student_id)) {
            $return = $return->where('student_attendance.student_id', '=', $request->student_id);
        }

        if (!empty($request->student_first_name)) {
            $return = $return->where('student.name', 'like', '%' . $request->student_first_name . '%');
        }

        if (!empty($request->student_last_name)) {
            $return = $return->where('student.last_name', 'like', '%' . $request->student_last_name . '%');
        }

        if (!empty($request->class_id)) {
            $return = $return->where('student_attendance.class_id', '=', $request->class_id);
        }

        if (!empty($request->from_attendance_date)) {
            $return = $return->whereDate('student_attendance.attendance_date', '>=', $request->from_attendance_date);
        }

        if (!empty($request->to_attendance_date)) {
            $return = $return->whereDate('student_attendance.attendance_date', '<=', $request->to_attendance_date);
        }

        if (!empty($request->attendance_type)) {
            $return = $return->where('student_attendance.attendance_type', '=', $request->attendance_type);
        }

        $return = $return->orderBy('student_attendance.id', 'desc')->paginate(20);

        $data['getRecord'] = $return;
        $data['header_title'] = 'Attendance Report';
        return view('admin.attendance.report', $data);
    }

    public function AttendanceReportTeacher(Request $request)
    {
        $getClass = AssignClassTeacher::select('assign_class_teacher.*', 'class.name as class_name', 'class.id as class_id',)
            ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
            ->where('assign_class_teacher.is_delete', '=', 0)
            ->where('assign_class_teacher.status', '=', 0)
            ->where('assign_class_teacher.teacher_id', '=', Auth::user()->id)
            ->groupBy('assign_class_teacher.class_id')->get();

        $classarray = array();
        foreach ($getClass as $value) {
            $classarray[] = $value->class_id;
        }
        $data['getClass'] = $getClass;
        if (!empty($classarray)) {
            $return = StudentAttendance::select('student_attendance.*', 'class.name as class_name', 'student.name as student_name', 'student.last_name as student_last_name', 'createdby.name as createdby_name')
                ->join('class', 'class.id', '=', 'student_attendance.class_id')
                ->join('users as student', 'student.id', '=', 'student_attendance.student_id')
                ->join('users as createdby', 'createdby.id', '=', 'student_attendance.created_by')
                ->whereIn('student_attendance.class_id', $classarray);

            if (!empty($request->student_id)) {
                $return = $return->where('student_attendance.student_id', '=', $request->student_id);
            }

            if (!empty($request->student_first_name)) {
                $return = $return->where('student.name', 'like', '%' . $request->student_first_name . '%');
            }

            if (!empty($request->student_last_name)) {
                $return = $return->where('student.last_name', 'like', '%' . $request->student_last_name . '%');
            }

            if (!empty($request->class_id)) {
                $return = $return->where('student_attendance.class_id', '=', $request->class_id);
            }

            if (!empty($request->from_attendance_date)) {
                $return = $return->whereDate('student_attendance.attendance_date', '>=', $request->from_attendance_date);
            }

            if (!empty($request->to_attendance_date)) {
                $return = $return->whereDate('student_attendance.attendance_date', '<=', $request->to_attendance_date);
            }

            if (!empty($request->attendance_type)) {
                $return = $return->where('student_attendance.attendance_type', '=', $request->attendance_type);
            }

            $return = $return->orderBy('student_attendance.id', 'desc')->paginate(20);

            $data['getRecord'] = $return;
        } else {
            $data['getRecord'] = "";
        }

        $data['header_title'] = 'Attendance Report';
        return view('teacher.attendance.report', $data);
    }

    //Student Side
    public function StudentMyAttendance(Request $request)
    {
        $return = StudentAttendance::select('student_attendance.*', 'class.name as class_name')
            ->join('class', 'class.id', '=', 'student_attendance.class_id')
            ->where('student_attendance.student_id', Auth::user()->id);

        if (!empty($request->from_attendance_date)) {
            $return = $return->whereDate('student_attendance.attendance_date', '>=', $request->from_attendance_date);
        }

        if (!empty($request->to_attendance_date)) {
            $return = $return->whereDate('student_attendance.attendance_date', '<=', $request->to_attendance_date);
        }

        if (!empty($request->attendance_type)) {
            $return = $return->where('student_attendance.attendance_type', '=', $request->attendance_type);
        }

        $return = $return->orderBy('student_attendance.id', 'desc')->paginate(20);

        $data['getRecord'] = $return;
        $data['header_title'] = 'My Attendance';
        return view('student.myattendance', $data);
    }

    //Parent side
    public function StudentAttendanceParent($student_id, Request $request)
    {
        $return = StudentAttendance::select('student_attendance.*', 'class.name as class_name')
            ->join('class', 'class.id', '=', 'student_attendance.class_id')
            ->where('student_attendance.student_id', $student_id);

        if (!empty($request->from_attendance_date)) {
            $return = $return->whereDate('student_attendance.attendance_date', '>=', $request->from_attendance_date);
        }

        if (!empty($request->to_attendance_date)) {
            $return = $return->whereDate('student_attendance.attendance_date', '<=', $request->to_attendance_date);
        }

        if (!empty($request->attendance_type)) {
            $return = $return->where('student_attendance.attendance_type', '=', $request->attendance_type);
        }

        $return = $return->orderBy('student_attendance.id', 'desc')->paginate(20);

        $data['getRecord'] = $return;
        $data['getStudent'] = User::find($student_id);
        $data['header_title'] = 'Student Attendance';
        return view('parent.myattendance', $data);
    }
}
