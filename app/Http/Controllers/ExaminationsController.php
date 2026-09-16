<?php

namespace App\Http\Controllers;

use App\Models\AssignClassTeacher;
use App\Models\ClassModel;
use App\Models\ClassSubject;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\MarksGrade;
use App\Models\MarksRegister;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExaminationsController extends Controller
{
    public function exam_list(Request $request)
    {
        $return = Exam::select('exams.*', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'exams.created_by')
            ->where('exams.is_delete', '=', 0);

        if (!empty($request->name)) {
            $return = $return->where('exams.name', 'LIKE', '%' . $request->name . '%');
        }

        if (!empty($request->date)) {
            $return = $return->whereDate('exams.created_at', '=', $request->date);
        }

        $return = $return->orderBy('exams.id', 'desc')
            ->paginate(2);
        $data['getRecord'] = $return;
        $data['header_title'] = 'Exam list';
        return view('admin.examinations.exam.list', $data);
    }

    public function exam_add()
    {
        $data['header_title'] = 'Add New Exam';
        return view('admin.examinations.exam.add', $data);
    }

    public function exam_insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_name' => 'required|min:5|max:30|regex:/^[a-zA-Z0-9\s]+$/',
            'note' => 'required|min:5|max:200|regex:/^[a-zA-Z\s]+$/',
        ]);

        if ($validator->passes()) {

            $exam = new Exam;
            $exam->name = $request->exam_name;
            $exam->note = $request->note;
            $exam->created_by = auth()->user()->id;
            $exam->save();

            return redirect('admin/examinations/exam/list')->with('success', 'Exam added successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function exam_edit($id)
    {
        $data['getRecord'] = Exam::find($id);
        if (!empty($data['getRecord'])) {
            $data['header_title'] = 'Edit Exam';
            return view('admin.examinations.exam.edit', $data);
        } else {
            return redirect()->back()->with('error', 'Exam not found');
        }
    }

    public function exam_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'exam_name' => 'required|min:5|max:30|regex:/^[a-zA-Z0-9\s]+$/',
            'note' => 'required|min:5|max:200|regex:/^[a-zA-Z\s]+$/',
        ]);

        if ($validator->passes()) {

            $exam = Exam::find($id);
            $exam->name = $request->exam_name;
            $exam->note = $request->note;
            $exam->save();

            return redirect('admin/examinations/exam/list')->with('success', 'Exam updated successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function exam_delete($id)
    {
        $exam = Exam::find($id);
        $exam->is_delete = 1;
        $exam->save();

        return redirect('admin/examinations/exam/list')->with('success', 'Exam deleted successfully');
    }

    public function exam_schedule(Request $request)
    {
        $data['getClass'] = ClassModel::select('class.*')
            ->join('users', 'users.id', 'class.created_by')
            ->where('class.is_delete', '=', 0)
            ->where('class.status', '=', 0)
            ->orderBy('class.name', 'asc')
            ->get();

        $data['getExam'] = Exam::select('exams.*')
            ->join('users', 'users.id', '=', 'exams.created_by')
            ->where('exams.is_delete', '=', 0)
            ->orderBy('exams.name', 'asc')
            ->get();

        $result = array();
        if (!empty($request->get('class_id')) && $request->get('exam_id')) {
            $getSubject = ClassSubject::select('class_subject.*', 'subject.name as subject_name', 'subject.type as subject_type')
                ->join('class', 'class.id', '=', 'class_subject.class_id')
                ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
                ->join('users', 'users.id', '=', 'class_subject.created_by')
                ->where('class_subject.class_id', '=', $request->get('class_id'))
                ->where('class_subject.is_delete', '=', 0)
                ->where('class_subject.status', '=', 0)
                ->orderBy('class_subject.id', 'asc')
                ->get();
                
            foreach ($getSubject as $value) {

                $dataS[] = array();
                $dataS['subject_id'] = $value->subject_id;
                $dataS['class_id'] = $value->class_id;
                $dataS['subject_name'] = $value->subject_name;
                $dataS['subject_type'] = $value->subject_type;

                $ExamSchedule = ExamSchedule::where('exam_id', '=', $request->get('exam_id'))
                    ->where('class_id', '=', $request->get('class_id'))
                    ->where('subject_id', '=', $value->subject_id)->first();

                if (!empty($ExamSchedule)) {
                    $dataS['exam_date'] = $ExamSchedule->exam_date;
                    $dataS['start_time'] = $ExamSchedule->start_time;
                    $dataS['end_time'] = $ExamSchedule->end_time;
                    $dataS['room_number'] = $ExamSchedule->room_number;
                    $dataS['full_marks'] = $ExamSchedule->full_marks;
                    $dataS['passing_marks'] = $ExamSchedule->passing_marks;
                } else {
                    $dataS['exam_date'] = '';
                    $dataS['start_time'] = '';
                    $dataS['end_time'] = '';
                    $dataS['room_number'] = '';
                    $dataS['full_marks'] = '';
                    $dataS['passing_marks'] = '';
                }

                $result[] = $dataS;
            }

            $data['getRecord'] = $result;
        }

        $data['header_title'] = 'Exam Schedule';
        return view('admin.examinations.exam_schedule', $data);
    }

    public function exam_schedule_insert(Request $request)
    {
        ExamSchedule::where('exam_id', '=', $request->exam_id)
            ->where('class_id', '=', $request->class_id)->delete();

        if (!empty($request->schedule)) {
            foreach ($request->schedule as $schedule) {
                if (!empty($schedule['subject_id']) && !empty($schedule['exam_date']) && !empty($schedule['start_time']) && !empty($schedule['end_time']) && !empty($schedule['room_number']) && !empty($schedule['full_marks']) && !empty($schedule['passing_marks'])) {
                    $exam = new ExamSchedule;
                    $exam->exam_id = $request->exam_id;
                    $exam->class_id = $request->class_id;
                    $exam->subject_id = $schedule['subject_id'];
                    $exam->exam_date = $schedule['exam_date'];
                    $exam->start_time = $schedule['start_time'];
                    $exam->end_time = $schedule['end_time'];
                    $exam->room_number = $schedule['room_number'];
                    $exam->full_marks = $schedule['full_marks'];
                    $exam->passing_marks = $schedule['passing_marks'];
                    $exam->created_by = Auth::user()->id;
                    $exam->save();
                }
            }
        }
        return redirect()->back()->with('success', 'Exam Schedule Saved Successfully');
    }

    public function marks_register(Request $request)
    {
        $data['getClass'] = ClassModel::select('class.*')
            ->join('users', 'users.id', 'class.created_by')
            ->where('class.is_delete', '=', 0)
            ->where('class.status', '=', 0)->orderBy('class.name', 'asc')->get();

        $data['getExam'] = Exam::select('exams.*')
            ->join('users', 'users.id', '=', 'exams.created_by')
            ->where('exams.is_delete', '=', 0)->orderBy('exams.name', 'asc')->get();


        if (!empty($request->get('class_id')) && $request->get('exam_id')) {
            $data['getSubject'] = ExamSchedule::select('exam_schedule.*', 'subject.name as subject_name', 'subject.type as subject_type')
                ->join('subject', 'subject.id', '=', 'exam_schedule.subject_id')
                ->where('exam_schedule.exam_id', '=', $request->get('exam_id'))
                ->where('exam_schedule.class_id', '=', $request->get('class_id'))->get();

            $data['getStudent'] = User::select('users.id', 'users.name', 'users.last_name')
                ->where('users.user_type', '=', 3)
                ->where('users.is_delete', '=', 0)
                ->where('users.status', '=', 0)
                ->where('users.class_id', '=', $request->get('class_id'))->orderBy('users.id', 'desc')->get();
        }

        $data['header_title'] = 'Marks Register';
        return view('admin.examinations.marks_register', $data);
    }

    //Teacher side
    public function marks_register_teacher(Request $request)
    {
        $data['getClass'] = AssignClassTeacher::select('assign_class_teacher.*', 'class.name as class_name', 'class.id as class_id',)
            ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
            ->where('assign_class_teacher.is_delete', '=', 0)
            ->where('assign_class_teacher.status', '=', 0)
            ->where('assign_class_teacher.teacher_id', '=', Auth::user()->id)
            ->groupBy('assign_class_teacher.class_id')->get();

        $data['getExam'] = ExamSchedule::select('exam_schedule.*', 'exams.name as exam_name', 'exams.id as exam_id')
            ->join('exams', 'exams.id', '=', 'exam_schedule.exam_id')
            ->join('assign_class_teacher', 'assign_class_teacher.class_id', '=', 'exam_schedule.class_id')
            ->where('assign_class_teacher.teacher_id', '=', Auth::user()->id)
            ->groupBy('exam_schedule.exam_id')->orderBy('exam_schedule.id', 'desc')->get();


        if (!empty($request->get('class_id')) && $request->get('exam_id')) {
            $data['getSubject'] = ExamSchedule::select('exam_schedule.*', 'subject.name as subject_name', 'subject.type as subject_type')
                ->join('subject', 'subject.id', '=', 'exam_schedule.subject_id')
                ->where('exam_schedule.exam_id', '=', $request->get('exam_id'))
                ->where('exam_schedule.class_id', '=', $request->get('class_id'))->get();

            $data['getStudent'] = User::select('users.id', 'users.name', 'users.last_name')
                ->where('users.user_type', '=', 3)
                ->where('users.is_delete', '=', 0)
                ->where('users.status', '=', 0)
                ->where('users.class_id', '=', $request->get('class_id'))->orderBy('users.id', 'desc')->get();
        }


        $data['header_title'] = 'Marks Register';
        return view('teacher.marks_register', $data);
    }

    public function submit_marks_register(Request $request)
    {
        $validation = 0;
        if (!empty($request->marks)) {
            foreach ($request->marks as $mark) {

                $getExamSchedule = ExamSchedule::find($mark['id']);
                $full_marks = $getExamSchedule->full_marks;

                $unit_test = !empty($mark['unit_test']) ? $mark['unit_test'] : 0;
                $half_yearly_exam = !empty($mark['half_yearly_exam']) ? $mark['half_yearly_exam'] : 0;
                $yearly_exam = !empty($mark['yearly_exam']) ? $mark['yearly_exam'] : 0;

                $total_marks = $unit_test + $half_yearly_exam + $yearly_exam;

                if ($full_marks  >= $total_marks) {
                    $getMark = MarksRegister::where('student_id', $request->student_id)
                        ->where('exam_id', $request->exam_id)
                        ->where('class_id', $request->class_id)
                        ->where('subject_id', $mark['subject_id'])->first();

                    if (!empty($getMark)) {
                        $save = $getMark;
                    } else {
                        $save = new MarksRegister;
                        $save->created_by = Auth::user()->id;
                    }

                    $save->student_id = $request->student_id;
                    $save->exam_id = $request->exam_id;
                    $save->class_id = $request->class_id;
                    $save->subject_id = $mark['subject_id'];
                    $save->unit_test = $unit_test;
                    $save->half_yearly_exam = $half_yearly_exam;
                    $save->yearly_exam = $yearly_exam;
                    $save->full_marks = $getExamSchedule->full_marks;
                    $save->passing_marks = $getExamSchedule->passing_marks;

                    $save->save();
                } else {
                    $validation = 1;
                }
            }
        }

        if ($validation == 0) {
            $json['message'] = "Mark Register Saved Successfully";
        } else {
            $json['message'] = "Mark Register Saved Successfully. But Some Subject Total marks greater than Full marks";
        }

        echo json_encode($json);
    }

    public function single_submit_marks_register(Request $request)
    {
        $getExamSchedule = ExamSchedule::find($request->id);
        $full_marks = $getExamSchedule->full_marks;

        $unit_test = !empty($request['unit_test']) ? $request->unit_test : 0;
        $half_yearly_exam = !empty($request['half_yearly_exam']) ? $request->half_yearly_exam : 0;
        $yearly_exam = !empty($request['yearly_exam']) ? $request->yearly_exam : 0;

        $total_marks = $unit_test + $half_yearly_exam + $yearly_exam;

        if ($full_marks  >= $total_marks) {
            $getMark = MarksRegister::where('student_id', $request->student_id)
                ->where('exam_id', $request->exam_id)
                ->where('class_id', $request->class_id)
                ->where('subject_id', $request->subject_id)->first();

            if (!empty($getMark)) {
                $save = $getMark;
            } else {
                $save = new MarksRegister;
                $save->created_by = Auth::user()->id;
            }

            $save->student_id = $request->student_id;
            $save->exam_id = $request->exam_id;
            $save->class_id = $request->class_id;
            $save->subject_id = $request->subject_id;
            $save->unit_test = $unit_test;
            $save->half_yearly_exam = $half_yearly_exam;
            $save->yearly_exam = $yearly_exam;
            $save->full_marks = $getExamSchedule->full_marks;
            $save->passing_marks = $getExamSchedule->passing_marks;
            $save->save();

            $json['message'] = "Mark Register Saved Successfully";
        } else {
            $json['message'] = "Total marks can not be greater than Full marks";
        }

        echo json_encode($json);
    }

    public function marks_grade_list()
    {
        $data['getRecord'] = MarksGrade::select('marks_grade.*', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'marks_grade.created_by')
            ->where('marks_grade.is_delete', '=', 0)->get();

        $data['header_title'] = 'Marks Grade';
        return view('admin.examinations.marks_grade.list', $data);
    }

    public function marks_grade_add()
    {
        $data['header_title'] = 'Add New Marks Grade';
        return view('admin.examinations.marks_grade.add', $data);
    }

    public function marks_grade_insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grade_name' => 'required|max:5|regex:/^[a-zA-Z0-9\s+]+$/',
            'percent_from' => 'required',
            'percent_to' => 'required',
        ]);

        if ($validator->passes()) {

            $marks_grade = new MarksGrade;
            $marks_grade->name = $request->grade_name;
            $marks_grade->percent_from = $request->percent_from;
            $marks_grade->percent_to = $request->percent_to;
            $marks_grade->created_by = auth()->user()->id;
            $marks_grade->save();

            return redirect('admin/examinations/marks_grade')->with('success', 'Marks Grade added successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function marks_grade_edit($id)
    {
        $data['getRecord'] = MarksGrade::find($id);
        if (!empty($data['getRecord'])) {
            $data['header_title'] = 'Edit Marks Grade';
            return view('admin.examinations.marks_grade.edit', $data);
        } else {
            return redirect()->back()->with('error', 'Marks Grade not found');
        }
    }

    public function marks_grade_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'grade_name' => 'required|max:5|regex:/^[a-zA-Z0-9\s+]+$/',
            'percent_from' => 'required',
            'percent_to' => 'required',
        ]);

        if ($validator->passes()) {

            $marks_grade = MarksGrade::find($id);
            $marks_grade->name = $request->grade_name;
            $marks_grade->percent_from = $request->percent_from;
            $marks_grade->percent_to = $request->percent_to;
            $marks_grade->save();

            return redirect('admin/examinations/marks_grade')->with('success', 'Marks Grade updated successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function marks_grade_delete($id)
    {
        $marks_grade = MarksGrade::find($id);
        $marks_grade->is_delete = 1;
        $marks_grade->save();

        return redirect()->back()->with('success', 'Marks Grade deleted successfully');
    }


    //Student Side
    public function myExamTimetable()
    {
        $class_id = Auth::user()->class_id;
        $getExamSchedule = ExamSchedule::select('exam_schedule.*', 'exams.name as exam_name')
            ->join('exams', 'exams.id', '=', 'exam_schedule.exam_id')
            ->where('exam_schedule.class_id', '=', $class_id)
            ->groupBy('exam_id')->orderBy('exam_schedule.id', 'desc')->get();

        $result = array();
        foreach ($getExamSchedule as $value) {
            $dataE = array();
            $dataE['name'] = $value->exam_name;
            $getExamDetails = ExamSchedule::select('exam_schedule.*', 'subject.name as subject_name', 'subject.type as subject_type')
                ->join('subject', 'subject.id', '=', 'exam_schedule.subject_id')
                ->where('exam_schedule.exam_id', '=', $value->exam_id)
                ->where('exam_schedule.class_id', '=', $class_id)->get();
            $resultS = array();
            foreach ($getExamDetails as $valueS) {
                $dataS = array();
                $dataS['subject_name'] = $valueS->subject_name;
                $dataS['exam_date'] = $valueS->exam_date;
                $dataS['start_time'] = $valueS->start_time;
                $dataS['end_time'] = $valueS->end_time;
                $dataS['room_number'] = $valueS->room_number;
                $dataS['full_marks'] = $valueS->full_marks;
                $dataS['passing_marks'] = $valueS->passing_marks;
                $resultS[] = $dataS;
            }
            $dataE['exam'] = $resultS;
            $result[] = $dataE;
        }

        $data['getRecord'] = $result;

        $data['header_title'] = 'My Exam Timetable';
        return view('student.myexamtimetable', $data);
    }

    public function myExamResult()
    {
        $result = array();
        $getExam = MarksRegister::select('marks_register.*', 'exams.name as exam_name')
            ->join('exams', 'exams.id', '=', 'marks_register.exam_id')
            ->where('marks_register.student_id', '=', Auth::user()->id)
            ->groupBy('marks_register.exam_id')->get();

        foreach ($getExam as $value) {
            $dataE = array();
            $dataE['exam_name'] = $value->exam_name;
            $getExamSubject = MarksRegister::select('marks_register.*', 'exams.name as exam_name', 'subject.name as subject_name')
                ->join('exams', 'exams.id', '=', 'marks_register.exam_id')
                ->join('subject', 'subject.id', '=', 'marks_register.subject_id')
                ->where('marks_register.exam_id', '=', $value->exam_id)
                ->where('marks_register.student_id', '=', Auth::user()->id)
                ->get();
            $dataSubject = array();
            foreach ($getExamSubject as $exam) {
                $total_marks = $exam['unit_test'] + $exam['half_yearly_exam'] + $exam['yearly_exam'];
                $dataS = array();
                $dataS['subject_name'] = $exam['subject_name'];
                $dataS['unit_test'] = $exam['unit_test'];
                $dataS['half_yearly_exam'] = $exam['half_yearly_exam'];
                $dataS['yearly_exam'] = $exam['yearly_exam'];
                $dataS['total_marks'] = $total_marks;
                $dataS['full_marks'] = $exam['full_marks'];
                $dataS['passing_marks'] = $exam['passing_marks'];
                $dataSubject[] = $dataS;
            }
            $dataE['subject'] = $dataSubject;
            $result[] = $dataE;
        }

        $data['getRecord'] = $result;
        $data['header_title'] = 'My Exam Result';
        return view('student.myexamresult', $data);
    }

    //Teacher Side
    public function myExamTimetableTeacher()
    {
        $result = array();
        $getClass = AssignClassTeacher::select('assign_class_teacher.*', 'class.name as class_name', 'class.id as class_id',)
            ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
            ->where('assign_class_teacher.is_delete', '=', 0)
            ->where('assign_class_teacher.status', '=', 0)
            ->where('assign_class_teacher.teacher_id', '=', Auth::user()->id)
            ->groupBy('assign_class_teacher.class_id')->get();
        foreach ($getClass as $class) {
            $dataC = array();
            $dataC['class_name'] = $class->class_name;

            $getExamSchedule = ExamSchedule::select('exam_schedule.*', 'exams.name as exam_name')
                ->join('exams', 'exams.id', '=', 'exam_schedule.exam_id')
                ->where('exam_schedule.class_id', '=', $class->class_id)
                ->groupBy('exam_schedule.exam_id')->orderBy('exam_schedule.id', 'asc')->get();

            $examArray = array();
            foreach ($getExamSchedule as $exam) {
                $dataE = array();
                $dataE['exam_name'] = $exam->exam_name;

                $getExamDetails = ExamSchedule::select('exam_schedule.*', 'subject.name as subject_name', 'subject.type as subject_type')
                    ->join('subject', 'subject.id', '=', 'exam_schedule.subject_id')
                    ->where('exam_schedule.exam_id', '=', $exam->exam_id)
                    ->where('exam_schedule.class_id', '=', $class->class_id)->get();
                $subjectArray = array();
                foreach ($getExamDetails as $valueS) {
                    $dataS = array();
                    $dataS['subject_name'] = $valueS->subject_name;
                    $dataS['exam_date'] = $valueS->exam_date;
                    $dataS['start_time'] = $valueS->start_time;
                    $dataS['end_time'] = $valueS->end_time;
                    $dataS['room_number'] = $valueS->room_number;
                    $dataS['full_marks'] = $valueS->full_marks;
                    $dataS['passing_marks'] = $valueS->passing_marks;
                    $subjectArray[] = $dataS;
                }

                $dataE['subject'] = $subjectArray;
                $examArray[] = $dataE;
            }

            $dataC['exam'] = $examArray;

            $result[] = $dataC;
        }

        $data['getRecord'] = $result;
        $data['header_title'] = 'My Exam Timetable';
        return view('teacher.myexamtimetable', $data);
    }

    //Parent Side
    public function myExamTimetableParent($student_id)
    {
        $getStudent = User::find($student_id);
        $class_id = $getStudent->class_id;
        $getExamSchedule = ExamSchedule::select('exam_schedule.*', 'exams.name as exam_name')
            ->join('exams', 'exams.id', '=', 'exam_schedule.exam_id')
            ->where('exam_schedule.class_id', '=', $class_id)
            ->groupBy('exam_id')->orderBy('exam_schedule.id', 'desc')->get();

        $result = array();
        foreach ($getExamSchedule as $value) {
            $dataE = array();
            $dataE['name'] = $value->exam_name;
            $getExamDetails = ExamSchedule::select('exam_schedule.*', 'subject.name as subject_name', 'subject.type as subject_type')
                ->join('subject', 'subject.id', '=', 'exam_schedule.subject_id')
                ->where('exam_schedule.exam_id', '=', $value->exam_id)
                ->where('exam_schedule.class_id', '=', $class_id)->get();
            $resultS = array();
            foreach ($getExamDetails as $valueS) {
                $dataS = array();
                $dataS['subject_name'] = $valueS->subject_name;
                $dataS['exam_date'] = $valueS->exam_date;
                $dataS['start_time'] = $valueS->start_time;
                $dataS['end_time'] = $valueS->end_time;
                $dataS['room_number'] = $valueS->room_number;
                $dataS['full_marks'] = $valueS->full_marks;
                $dataS['passing_marks'] = $valueS->passing_marks;
                $resultS[] = $dataS;
            }
            $dataE['exam'] = $resultS;
            $result[] = $dataE;
        }

        $data['getRecord'] = $result;
        $data['getStudent'] = $getStudent;
        $data['header_title'] = 'Student Exam Timetable';
        return view('parent.myexamtimetable', $data);
    }

    public function myExamResultParent($student_id)
    {
        $data['getStudent'] = User::find($student_id);

        $result = array();
        $getExam = MarksRegister::select('marks_register.*', 'exams.name as exam_name')
            ->join('exams', 'exams.id', '=', 'marks_register.exam_id')
            ->where('marks_register.student_id', '=', $student_id)
            ->groupBy('marks_register.exam_id')->get();

        foreach ($getExam as $value) {
            $dataE = array();
            $dataE['exam_name'] = $value->exam_name;
            $getExamSubject = MarksRegister::select('marks_register.*', 'exams.name as exam_name', 'subject.name as subject_name')
                ->join('exams', 'exams.id', '=', 'marks_register.exam_id')
                ->join('subject', 'subject.id', '=', 'marks_register.subject_id')
                ->where('marks_register.exam_id', '=', $value->exam_id)
                ->where('marks_register.student_id', '=', $student_id)
                ->get();
            $dataSubject = array();
            foreach ($getExamSubject as $exam) {
                $total_marks = $exam['unit_test'] + $exam['half_yearly_exam'] + $exam['yearly_exam'];
                $dataS = array();
                $dataS['subject_name'] = $exam['subject_name'];
                $dataS['unit_test'] = $exam['unit_test'];
                $dataS['half_yearly_exam'] = $exam['half_yearly_exam'];
                $dataS['yearly_exam'] = $exam['yearly_exam'];
                $dataS['total_marks'] = $total_marks;
                $dataS['full_marks'] = $exam['full_marks'];
                $dataS['passing_marks'] = $exam['passing_marks'];
                $dataSubject[] = $dataS;
            }
            $dataE['subject'] = $dataSubject;
            $result[] = $dataE;
        }

        $data['getRecord'] = $result;
        $data['header_title'] = 'Student Exam Result';
        return view('parent.myexamresult', $data);
    }
}
