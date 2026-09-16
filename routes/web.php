<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssignClassTeacherController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\ClassTimetableController;
use App\Http\Controllers\CommunicateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExaminationsController;
use App\Http\Controllers\FeesCollectionController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [AuthController::class, 'login']);
Route::post('login', [AuthController::class, 'AuthLogin']);
Route::get('logout', [AuthController::class, 'logout']);
Route::get('forgotpassword', [AuthController::class, 'forgotpassword']);
Route::post('forgotpassword', [AuthController::class, 'PostForgotPassword']);


Route::group(['middleware' => 'admin'], function () {
    Route::get('admin/dashboard', [DashboardController::class, 'dashboard']);

    //Admin List Routes
    Route::get('admin/admin/list', [AdminController::class, 'list']);
    Route::get('admin/admin/add', [AdminController::class, 'add']);
    Route::post('admin/admin/add', [AdminController::class, 'insert']);
    Route::get('admin/admin/edit/{id}', [AdminController::class, 'edit']);
    Route::post('admin/admin/edit/{id}', [AdminController::class, 'update']);
    Route::get('admin/admin/delete/{id}', [AdminController::class, 'delete']);

    //Teacher List Routes
    Route::get('admin/teacher/list', [TeacherController::class, 'list']);
    Route::get('admin/teacher/add', [TeacherController::class, 'add']);
    Route::post('admin/teacher/add', [TeacherController::class, 'insert']);
    Route::get('admin/teacher/edit/{id}', [TeacherController::class, 'edit']);
    Route::post('admin/teacher/edit/{id}', [TeacherController::class, 'update']);
    Route::get('admin/teacher/delete/{id}', [TeacherController::class, 'delete']);

    //Student List Routes
    Route::get('admin/student/list', [StudentController::class, 'list']);
    Route::get('admin/student/add', [StudentController::class, 'add']);
    Route::post('admin/student/add', [StudentController::class, 'insert']);
    Route::get('admin/student/edit/{id}', [StudentController::class, 'edit']);
    Route::post('admin/student/edit/{id}', [StudentController::class, 'update']);
    Route::get('admin/student/delete/{id}', [StudentController::class, 'delete']);

    //Parent List Routes
    Route::get('admin/parent/list', [ParentController::class, 'list']);
    Route::get('admin/parent/add', [ParentController::class, 'add']);
    Route::post('admin/parent/add', [ParentController::class, 'insert']);
    Route::get('admin/parent/edit/{id}', [ParentController::class, 'edit']);
    Route::post('admin/parent/edit/{id}', [ParentController::class, 'update']);
    Route::get('admin/parent/delete/{id}', [ParentController::class, 'delete']);
    Route::get('admin/parent/my-student/{id}', [ParentController::class, 'myStudent']);
    Route::get('admin/parent/assign_student_parent/{student_id}/{parent_id}', [ParentController::class, 'assignStudentParent']);
    Route::get('admin/parent/assign_student_parent_delete/{student_id}', [ParentController::class, 'assignStudentParentDelete']);

    //Class Routes
    Route::get('admin/class/list', [ClassController::class, 'list']);
    Route::get('admin/class/add', [ClassController::class, 'add']);
    Route::post('admin/class/add', [ClassController::class, 'insert']);
    Route::get('admin/class/edit/{id}', [ClassController::class, 'edit']);
    Route::post('admin/class/edit/{id}', [ClassController::class, 'update']);
    Route::get('admin/class/delete/{id}', [ClassController::class, 'delete']);

    //Subject Routes
    Route::get('admin/subject/list', [SubjectController::class, 'list']);
    Route::get('admin/subject/add', [SubjectController::class, 'add']);
    Route::post('admin/subject/add', [SubjectController::class, 'insert']);
    Route::get('admin/subject/edit/{id}', [SubjectController::class, 'edit']);
    Route::post('admin/subject/edit/{id}', [SubjectController::class, 'update']);
    Route::get('admin/subject/delete/{id}', [SubjectController::class, 'delete']);

    //Assign Subject Routes
    Route::get('admin/assign_subject/list', [ClassSubjectController::class, 'list']);
    Route::get('admin/assign_subject/add', [ClassSubjectController::class, 'add']);
    Route::post('admin/assign_subject/add', [ClassSubjectController::class, 'insert']);
    Route::get('admin/assign_subject/edit/{id}', [ClassSubjectController::class, 'edit']);
    Route::post('admin/assign_subject/edit/{id}', [ClassSubjectController::class, 'update']);
    Route::get('admin/assign_subject/delete/{id}', [ClassSubjectController::class, 'delete']);
    Route::get('admin/assign_subject/edit_single/{id}', [ClassSubjectController::class, 'edit_single']);
    Route::post('admin/assign_subject/edit_single/{id}', [ClassSubjectController::class, 'update_single']);

    //Class Timetable Routes
    Route::get('admin/class_timetable/list', [ClassTimetableController::class, 'list']);
    Route::post('admin/class_timetable/get_subject', [ClassTimetableController::class, 'get_subject']);
    Route::post('admin/class_timetable/add', [ClassTimetableController::class, 'insert_update']);

    //Assign Class to Teacher
    Route::get('admin/assign_class_teacher/list', [AssignClassTeacherController::class, 'list']);
    Route::get('admin/assign_class_teacher/add', [AssignClassTeacherController::class, 'add']);
    Route::post('admin/assign_class_teacher/add', [AssignClassTeacherController::class, 'insert']);
    Route::get('admin/assign_class_teacher/edit/{id}', [AssignClassTeacherController::class, 'edit']);
    Route::post('admin/assign_class_teacher/edit/{id}', [AssignClassTeacherController::class, 'update']);
    Route::get('admin/assign_class_teacher/delete/{id}', [AssignClassTeacherController::class, 'delete']);
    Route::get('admin/assign_class_teacher/edit_single/{id}', [AssignClassTeacherController::class, 'edit_single']);
    Route::post('admin/assign_class_teacher/edit_single/{id}', [AssignClassTeacherController::class, 'update_single']);

    //Exam List Routes
    Route::get('admin/examinations/exam/list', [ExaminationsController::class, 'exam_list']);
    Route::get('admin/examinations/exam/add', [ExaminationsController::class, 'exam_add']);
    Route::post('admin/examinations/exam/add', [ExaminationsController::class, 'exam_insert']);
    Route::get('admin/examinations/exam/edit/{id}', [ExaminationsController::class, 'exam_edit']);
    Route::post('admin/examinations/exam/edit/{id}', [ExaminationsController::class, 'exam_update']);
    Route::get('admin/examinations/exam/delete/{id}', [ExaminationsController::class, 'exam_delete']);

    //Exam Schedule Routes
    Route::get('admin/examinations/exam_schedule', [ExaminationsController::class, 'exam_schedule']);
    Route::post('admin/examinations/exam_schedule_insert', [ExaminationsController::class, 'exam_schedule_insert']);

    //Marks Register Routes
    Route::get('admin/examinations/marks_register', [ExaminationsController::class, 'marks_register']);
    Route::post('admin/examinations/submit_marks_register', [ExaminationsController::class, 'submit_marks_register']);
    Route::post('admin/examinations/single_submit_marks_register', [ExaminationsController::class, 'single_submit_marks_register']);

    //Marks Grade Routes
    Route::get('admin/examinations/marks_grade', [ExaminationsController::class, 'marks_grade_list']);
    Route::get('admin/examinations/marks_grade/add', [ExaminationsController::class, 'marks_grade_add']);
    Route::post('admin/examinations/marks_grade/add', [ExaminationsController::class, 'marks_grade_insert']);
    Route::get('admin/examinations/marks_grade/edit/{id}', [ExaminationsController::class, 'marks_grade_edit']);
    Route::post('admin/examinations/marks_grade/edit/{id}', [ExaminationsController::class, 'marks_grade_update']);
    Route::get('admin/examinations/marks_grade/delete/{id}', [ExaminationsController::class, 'marks_grade_delete']);

    //Attendance Routes
    Route::get('admin/attendance/student', [AttendanceController::class, 'StudentAttendance']);
    Route::post('admin/attendance/student/save', [AttendanceController::class, 'StudentAttendanceSubmit']);
    Route::get('admin/attendance/report', [AttendanceController::class, 'AttendanceReport']);

    //Notice Board Routes
    Route::get('admin/communicate/notice_board', [CommunicateController::class, 'NoticeBoard']);
    Route::get('admin/communicate/notice_board/add', [CommunicateController::class, 'AddNoticeBoard']);
    Route::post('admin/communicate/notice_board/add', [CommunicateController::class, 'InsertNoticeBoard']);
    Route::get('admin/communicate/notice_board/edit/{id}', [CommunicateController::class, 'EditNoticeBoard']);
    Route::post('admin/communicate/notice_board/edit/{id}', [CommunicateController::class, 'UpdateNoticeBoard']);
    Route::get('admin/communicate/notice_board/delete/{id}', [CommunicateController::class, 'DeleteNoticeBoard']);
    Route::get('admin/communicate/send_email', [CommunicateController::class, 'SendEmail']);
    Route::post('admin/communicate/send_email', [CommunicateController::class, 'SendEmailUser']);
    Route::get('admin/communicate/search_user', [CommunicateController::class, 'SearchUser']);

    //Homework Routes
    Route::get('admin/homework/homework', [HomeworkController::class, 'Homework']);
    Route::get('admin/homework/homework/add', [HomeworkController::class, 'AddHomework']);
    Route::post('admin/homework/homework/add', [HomeworkController::class, 'InsertHomework']);
    Route::post('admin/homework/getsubject', [HomeworkController::class, 'GetSubject']);
    Route::get('admin/homework/homework/edit/{id}', [HomeworkController::class, 'EditHomework']);
    Route::post('admin/homework/homework/edit/{id}', [HomeworkController::class, 'UpdateHomework']);
    Route::get('admin/homework/homework/delete/{id}', [HomeworkController::class, 'DeleteHomework']);
    Route::get('admin/homework/homework/submitted/{id}', [HomeworkController::class, 'Submitted']);
    Route::get('admin/homework/report', [HomeworkController::class, 'HomeworkReport']);

    //Fees Collection Routes
    Route::get('admin/fees_collection/collect_fees', [FeesCollectionController::class, 'CollectFees']);
    Route::get('admin/fees_collection/collect_fees/add_fees/{student_id}', [FeesCollectionController::class, 'AddCollectFees']);
    Route::post('admin/fees_collection/collect_fees/add_fees/{student_id}', [FeesCollectionController::class, 'InsertCollectFees']);
    Route::get('admin/fees_collection/collect_fees_report', [FeesCollectionController::class, 'CollectFeesReport']);

    //Account Routes
    Route::get('admin/account', [UserController::class, 'MyAccount']);
    Route::post('admin/account', [UserController::class, 'UpdateAdminMyAccount']);
    Route::get('admin/setting', [UserController::class, 'Setting']);
    Route::post('admin/setting', [UserController::class, 'UpdateSetting']);

    //Change Password Routes
    Route::get('admin/change_password', [UserController::class, 'change_password']);
    Route::post('admin/change_password', [UserController::class, 'update_change_password']);
});

Route::group(['middleware' => 'teacher'], function () {
    Route::get('teacher/dashboard', [DashboardController::class, 'dashboard']);

    //Change Password Routes
    Route::get('teacher/change_password', [UserController::class, 'change_password']);
    Route::post('teacher/change_password', [UserController::class, 'update_change_password']);

    //Account Routes
    Route::get('teacher/account', [UserController::class, 'MyAccount']);
    Route::post('teacher/account', [UserController::class, 'UpdateTeacherMyAccount']);

    //Classs & Subject Routes
    Route::get('teacher/my_class_subject', [AssignClassTeacherController::class, 'myClassSubject']);
    //Timetable Routes
    Route::get('teacher/my_class_subject/class_timetable/{class_id}/{subject_id}', [ClassTimetableController::class, 'myTimetableTeacher']);

    //My Student Routes
    Route::get('teacher/my_student', [StudentController::class, 'myStudent']);

    //My Exam Timetable Routes
    Route::get('teacher/my_exam_timetable', [ExaminationsController::class, 'myExamTimetableTeacher']);

    //Marks Register Routes
    Route::get('teacher/marks_register', [ExaminationsController::class, 'marks_register_teacher']);
    Route::post('teacher/submit_marks_register', [ExaminationsController::class, 'submit_marks_register']);
    Route::post('teacher/single_submit_marks_register', [ExaminationsController::class, 'single_submit_marks_register']);

    //Attendance Routes
    Route::get('teacher/attendance/student', [AttendanceController::class, 'StudentAttendanceTeacher']);
    Route::post('teacher/attendance/student/save', [AttendanceController::class, 'StudentAttendanceSubmit']);
    Route::get('teacher/attendance/report', [AttendanceController::class, 'AttendanceReportTeacher']);

    //Notice Board Routes
    Route::get('teacher/my_notice_board', [CommunicateController::class, 'MyNoticeBoardTeacher']);

    //Homework Routes
    Route::get('teacher/homework/homework', [HomeworkController::class, 'HomeworkTeacher']);
    Route::get('teacher/homework/homework/add', [HomeworkController::class, 'AddHomework']);
    Route::post('teacher/homework/homework/add', [HomeworkController::class, 'InsertHomework']);
    Route::post('teacher/homework/getsubject', [HomeworkController::class, 'GetSubject']);
    Route::get('teacher/homework/homework/edit/{id}', [HomeworkController::class, 'EditHomework']);
    Route::post('teacher/homework/homework/edit/{id}', [HomeworkController::class, 'UpdateHomework']);
    Route::get('teacher/homework/homework/delete/{id}', [HomeworkController::class, 'DeleteHomework']);
    Route::get('teacher/homework/homework/submitted/{id}', [HomeworkController::class, 'Submitted']);
});

Route::group(['middleware' => 'student'], function () {
    Route::get('student/dashboard', [DashboardController::class, 'dashboard']);

    //Change Password Routes
    Route::get('student/change_password', [UserController::class, 'change_password']);
    Route::post('student/change_password', [UserController::class, 'update_change_password']);

    //Account Routes
    Route::get('student/account', [UserController::class, 'MyAccount']);
    Route::post('student/account', [UserController::class, 'UpdateStudentMyAccount']);

    //Subject Routes
    Route::get('student/my_subject', [SubjectController::class, 'mySubject']);

    //My Timetable Routes
    Route::get('student/my_timetable', [ClassTimetableController::class, 'myTimetable']);

    //My Exam Timetable Routes
    Route::get('student/my_exam_timetable', [ExaminationsController::class, 'myExamTimetable']);

    //My Exam result Routes
    Route::get('student/my_exam_result', [ExaminationsController::class, 'myExamResult']);

    //Attendance Routes
    Route::get('student/my_attendance', [AttendanceController::class, 'StudentMyAttendance']);

    //Notice Board Routes
    Route::get('student/my_notice_board', [CommunicateController::class, 'MyNoticeBoard']);

    //Homework Routes
    Route::get('student/my_homework', [HomeworkController::class, 'HomeworkStudent']);
    Route::get('student/my_homework/submit_homework/{id}', [HomeworkController::class, 'SubmitHomework']);
    Route::post('student/my_homework/submit_homework/{id}', [HomeworkController::class, 'InsertSubmitHomework']);
    Route::get('student/my_submitted_homework', [HomeworkController::class, 'SubmittedHomework']);

    //Fees Routes
    Route::get('student/fees_collection', [FeesCollectionController::class, 'StudentCollectFees']);

});

Route::group(['middleware' => 'parent'], function () {
    Route::get('parent/dashboard', [DashboardController::class, 'dashboard']);

    //Change Password Routes
    Route::get('parent/change_password', [UserController::class, 'change_password']);
    Route::post('parent/change_password', [UserController::class, 'update_change_password']);

    //Account Routes
    Route::get('parent/account', [UserController::class, 'MyAccount']);
    Route::post('parent/account', [UserController::class, 'UpdateParentMyAccount']);

    Route::get('parent/my_student', [ParentController::class, 'MyStudentParent']);
    Route::get('parent/my_student/subject/{student_id}', [SubjectController::class, 'ParentStudentSubject']);

    //Timetable Routes
    Route::get('parent/my_student/subject/class_timetable/{class_id}/{subject_id}', [ClassTimetableController::class, 'myTimetableParent']);

    //Exam Timetable Routes
    Route::get('parent/my_student/exam_timetable/{student_id}', [ExaminationsController::class, 'myExamTimetableParent']);

    //Exam Result Routes
    Route::get('parent/my_student/exam_result/{student_id}', [ExaminationsController::class, 'myExamResultParent']);

    //Attendance Routes
    Route::get('parent/my_student/attendance/{student_id}', [AttendanceController::class, 'StudentAttendanceParent']);

    //Notice Board Routes
    Route::get('parent/my_notice_board', [CommunicateController::class, 'MyNoticeBoardParent']);

    //Homework Routes
    Route::get('parent/my_student/homework/{student_id}', [HomeworkController::class, 'HomeworkParent']);
    Route::get('parent/my_student/submitted_homework/{student_id}', [HomeworkController::class, 'SubmittedHomeworkParent']);

    //Fees Routes
    Route::get('parent/my_student/fees_collection/{student_id}', [FeesCollectionController::class, 'ParentCollectFees']);

});
