<?php

namespace App\Http\Controllers;

use App\Models\AssignClassTeacher;
use App\Models\ClassModel;
use App\Models\ClassSubject;
use App\Models\Exam;
use App\Models\Homework;
use App\Models\NoticeBoard;
use App\Models\StudentAddFees;
use App\Models\StudentAttendance;
use App\Models\Subject;
use App\Models\SubmitHomework;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data['header_title'] = 'Dashboard';
        if (Auth::user()->user_type == 1) {

            $data['getTotalTodayFees'] = StudentAddFees::getTotalTodayFees();
            $data['getTotalFees'] = StudentAddFees::getTotalFees();

            $data['getTotalAdmin'] = User::getTotalUser(1);
            $data['getTotalTeacher'] = User::getTotalUser(2);
            $data['getTotalStudent'] = User::getTotalUser(3);
            $data['getTotalParent'] = User::getTotalUser(4);

            $data['getTotalExam'] = Exam::getTotalExam();
            $data['getTotalClass'] = ClassModel::getTotalClass();
            $data['getTotalSubject'] = Subject::getTotalSubject();

            return view('admin.dashboard', $data);
        }

        if (Auth::user()->user_type == 2) {

            $data['getTotalStudent'] = User::getTotalStudent(Auth::user()->id);
            $data['getTotalClass'] = AssignClassTeacher::getTotalClass(Auth::user()->id);
            $data['getTotalSubject'] = AssignClassTeacher::getTotalSubject(Auth::user()->id);
            $data['getTotalNotice'] = NoticeBoard::getTotalNotice(Auth::user()->user_type);

            return view('teacher.dashboard', $data);
        }

        if (Auth::user()->user_type == 3) {

            $data['TotalFeesPaidAmount'] = StudentAddFees::TotalFeesPaidAmount(Auth::user()->id);
            $data['TotalSubject'] = ClassSubject::TotalSubject(Auth::user()->class_id);
            $data['getTotalNotice'] = NoticeBoard::getTotalNotice(Auth::user()->user_type);
            $data['getTotalHomework'] = Homework::getTotalHomework(Auth::user()->class_id, Auth::user()->id);
            $data['getTotalSubmittedHomework'] = SubmitHomework::getTotalSubmittedHomework(Auth::user()->id);
            $data['getTotalAttendance'] = StudentAttendance::getTotalAttendance(Auth::user()->id);
            return view('student.dashboard', $data);
        }

        if (Auth::user()->user_type == 4) {

            $student_ids = User::getMyStudentIds(Auth::user()->id);

            if(!empty($student_ids))
            {
                $data['TotalFeesPaidAmount'] = StudentAddFees::TotalFeesPaidAmountParent($student_ids);
                $data['getTotalAttendance'] = StudentAttendance::getTotalAttendanceParent($student_ids);
                $data['getTotalSubmittedHomework'] = SubmitHomework::getTotalSubmittedHomeworkParent($student_ids);
            }
            else
            {
                $data['TotalFeesPaidAmount'] = 0;
                $data['getTotalAttendance'] = 0;
                $data['getTotalSubmittedHomework'] = 0;
            }

            $data['getTotalFees'] = StudentAddFees::getTotalFees();
            $data['getTotalStudent'] = User::getTotalStudentCount(Auth::user()->id);
            $data['getTotalNotice'] = NoticeBoard::getTotalNotice(Auth::user()->user_type);

            return view('parent.dashboard', $data);
        }
    }
}
