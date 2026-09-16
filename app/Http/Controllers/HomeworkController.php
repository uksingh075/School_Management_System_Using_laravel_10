<?php

namespace App\Http\Controllers;

use App\Models\AssignClassTeacher;
use App\Models\ClassModel;
use App\Models\ClassSubject;
use App\Models\Homework;
use App\Models\SubmitHomework;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HomeworkController extends Controller
{
    public function HomeworkReport(Request $request)
    {
        $return = SubmitHomework::select('submit_homework.*', 'class.name as class_name', 'subject.name as subject_name', 'users.name as first_name', 'users.last_name as last_name')
            ->join('users', 'users.id', 'submit_homework.student_id')
            ->join('homework', 'homework.id', 'submit_homework.homework_id')
            ->join('class', 'class.id', 'homework.class_id')
            ->join('subject', 'subject.id', 'homework.subject_id');

        if (!empty($request->student_first_name)) {
            $return = $return->where('users.name', 'like', '%' . $request->student_first_name . '%');
        }

        if (!empty($request->student_last_name)) {
            $return = $return->where('users.last_name', 'like', '%' . $request->student_last_name . '%');
        }

        if (!empty($request->class)) {
            $return = $return->where('class.name', 'like', '%' . $request->class . '%');
        }

        if (!empty($request->subject)) {
            $return = $return->where('subject.name', 'like', '%' . $request->subject . '%');
        }

        if (!empty($request->from_homework_date)) {
            $return = $return->whereDate('homework.homework_date', '>=', $request->from_homework_date);
        }

        if (!empty($request->to_homework_date)) {
            $return = $return->whereDate('homework.homework_date', '<=', $request->to_homework_date);
        }

        if (!empty($request->from_submission_date)) {
            $return = $return->whereDate('homework.submission_date', '>=', $request->from_submission_date);
        }

        if (!empty($request->to_submission_date)) {
            $return = $return->whereDate('homework.submission_date', '<=', $request->to_submission_date);
        }

        if (!empty($request->from_submitted_date)) {
            $return = $return->whereDate('submit_homework.created_at', '>=', $request->from_submitted_date);
        }

        if (!empty($request->to_submitted_date)) {
            $return = $return->whereDate('submit_homework.created_at', '<=', $request->to_submitted_date);
        }

        $return = $return->orderBy('submit_homework.id', 'desc')->paginate(10);

        $data['getRecord'] = $return;
        $data['header_title'] = 'Homework Report';
        return view('admin.homework.report', $data);
    }
    public function Homework(Request $request)
    {
        $return = Homework::select('homework.*', 'class.name as class_name', 'subject.name as subject_name', 'users.name as created_by_name')
            ->join('class', 'class.id', 'homework.class_id')
            ->join('subject', 'subject.id', 'homework.subject_id')
            ->join('users', 'users.id', 'homework.created_by');

        if (!empty($request->class)) {
            $return = $return->where('class.name', 'like', '%' . $request->class . '%');
        }

        if (!empty($request->subject)) {
            $return = $return->where('subject.name', 'like', '%' . $request->subject . '%');
        }

        if (!empty($request->from_homework_date)) {
            $return = $return->whereDate('homework.homework_date', '>=', $request->from_homework_date);
        }

        if (!empty($request->to_homework_date)) {
            $return = $return->whereDate('homework.homework_date', '<=', $request->to_homework_date);
        }

        if (!empty($request->from_submission_date)) {
            $return = $return->whereDate('homework.submission_date', '>=', $request->from_submission_date);
        }

        if (!empty($request->to_submission_date)) {
            $return = $return->whereDate('homework.submission_date', '<=', $request->to_submission_date);
        }

        $return = $return->where('homework.is_delete', '=', 0)
            ->orderBy('homework.id', 'desc')->paginate(10);

        $data['getRecord'] = $return;
        $data['header_title'] = 'Homework';
        return view('admin.homework.list', $data);
    }

    public function AddHomework()
    {
        if (Auth::user()->user_type == 1) {

            $data['getClass'] = ClassModel::select('class.*')
                ->join('users', 'users.id', 'class.created_by')
                ->where('class.is_delete', '=', 0)
                ->where('class.status', '=', 0)->orderBy('class.name', 'asc')->get();

            $data['header_title'] = 'Add Homework';
            return view('admin.homework.add', $data);
        } elseif (Auth::user()->user_type == 2) {

            $data['getClass'] = AssignClassTeacher::select('assign_class_teacher.*', 'class.name as class_name', 'class.id as class_id',)
                ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
                ->where('assign_class_teacher.is_delete', '=', 0)
                ->where('assign_class_teacher.status', '=', 0)
                ->where('assign_class_teacher.teacher_id', '=', Auth::user()->id)
                ->groupBy('assign_class_teacher.class_id')->get();

            $data['header_title'] = 'Add Homework';
            return view('teacher.homework.add', $data);
        }
    }

    public function InsertHomework(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'subject_id' => 'required',
            'homework_date' => 'required',
            'submission_date' => 'required',
            'document' => 'required',
            'description' => 'required',
        ]);

        if ($validator->passes()) {

            $homework = new Homework;
            $homework->class_id = $request->class_id;
            $homework->subject_id = $request->subject_id;
            $homework->homework_date = $request->homework_date;
            $homework->submission_date = $request->submission_date;

            if (!empty($request->file('document'))) {

                $ext = $request->file('document')->getClientOriginalExtension();
                $file = $request->file('document');
                $randomStr = Str::random(20);
                $filename = strtolower($randomStr) . '.' . $ext;
                $file->move(public_path() . '/uploads/homework/', $filename);
                $homework->document = $filename;
            }

            $homework->description = $request->description;
            $homework->created_by = Auth::user()->id;
            $homework->save();

            if (Auth::user()->user_type == 1) {
                return redirect('admin/homework/homework')->with('success', 'Homework added successfully');
            } elseif (Auth::user()->user_type == 2) {
                return redirect('teacher/homework/homework')->with('success', 'Homework added successfully');
            }
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function EditHomework($id)
    {
        $data['getRecord'] = Homework::find($id);
        if (Auth::user()->user_type == 1) {

            $data['getClass'] = ClassModel::select('class.*')
                ->join('users', 'users.id', 'class.created_by')
                ->where('class.is_delete', '=', 0)
                ->where('class.status', '=', 0)->orderBy('class.name', 'asc')->get();

            $data['getSubject'] = ClassSubject::select('class_subject.*', 'subject.name as subject_name', 'subject.type as subject_type')
                ->join('class', 'class.id', '=', 'class_subject.class_id')
                ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
                ->join('users', 'users.id', '=', 'class_subject.created_by')
                ->where('class_subject.class_id', '=', $data['getRecord']->class_id)
                ->where('class_subject.is_delete', '=', 0)
                ->where('class_subject.status', '=', 0)
                ->orderBy('class_subject.id', 'asc')->get();

            $data['header_title'] = 'Edit Homework';
            return view('admin.homework.edit', $data);
        } elseif (Auth::user()->user_type == 2) {

            $data['getClass'] = AssignClassTeacher::select('assign_class_teacher.*', 'class.name as class_name', 'class.id as class_id',)
                ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
                ->where('assign_class_teacher.is_delete', '=', 0)
                ->where('assign_class_teacher.status', '=', 0)
                ->where('assign_class_teacher.teacher_id', '=', Auth::user()->id)
                ->groupBy('assign_class_teacher.class_id')->get();

            $data['getSubject'] = ClassSubject::select('class_subject.*', 'subject.name as subject_name', 'subject.type as subject_type')
                ->join('class', 'class.id', '=', 'class_subject.class_id')
                ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
                ->join('users', 'users.id', '=', 'class_subject.created_by')
                ->where('class_subject.class_id', '=', $data['getRecord']->class_id)
                ->where('class_subject.is_delete', '=', 0)
                ->where('class_subject.status', '=', 0)
                ->orderBy('class_subject.id', 'asc')->get();

            $data['header_title'] = 'Edit Homework';
            return view('teacher.homework.edit', $data);
        }
    }

    public function UpdateHomework(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'subject_id' => 'required',
            'homework_date' => 'required',
            'submission_date' => 'required',
            // 'document' => 'required',
            'description' => 'required',
        ]);

        if ($validator->passes()) {

            $homework = Homework::find($id);
            $homework->class_id = $request->class_id;
            $homework->subject_id = $request->subject_id;
            $homework->homework_date = $request->homework_date;
            $homework->submission_date = $request->submission_date;

            if (!empty($request->file('document'))) {

                $ext = $request->file('document')->getClientOriginalExtension();
                $file = $request->file('document');
                $randomStr = Str::random(20);
                $filename = strtolower($randomStr) . '.' . $ext;
                $file->move(public_path() . '/uploads/homework/', $filename);
                $homework->document = $filename;
            }

            $homework->description = $request->description;
            $homework->save();

            if (Auth::user()->user_type == 1) {
                return redirect('admin/homework/homework')->with('success', 'Homework updated successfully');
            } elseif (Auth::user()->user_type == 2) {
                return redirect('teacher/homework/homework')->with('success', 'Homework updated successfully');
            }
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function DeleteHomework($id)
    {
        $homework = Homework::find($id);
        $homework->is_delete = 1;
        $homework->save();
        return redirect()->back()->with('success', 'Homework deleted successfully');
    }


    public function GetSubject(Request $request)
    {
        $class_id = $request->class_id;
        $getSubject = ClassSubject::select('class_subject.*', 'subject.name as subject_name', 'subject.type as subject_type')
            ->join('class', 'class.id', '=', 'class_subject.class_id')
            ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
            ->join('users', 'users.id', '=', 'class_subject.created_by')
            ->where('class_subject.class_id', '=', $class_id)
            ->where('class_subject.is_delete', '=', 0)
            ->where('class_subject.status', '=', 0)
            ->orderBy('class_subject.id', 'asc')->get();

        $html = '';
        $html = '<option value="">Select Subject</option>';
        foreach ($getSubject as $subject) {
            $html .= '<option value="' . $subject->subject_id . '">' . $subject->subject_name . '</option>';
        }

        $json['success'] = $html;
        echo json_encode($json);
    }

    public function Submitted($homework_id, Request $request)
    {
        $homework = Homework::find($homework_id);
        if (!empty($homework)) {

            $return = SubmitHomework::select('submit_homework.*', 'users.name as first_name', 'users.last_name as last_name')
                ->join('users', 'users.id', '=', 'submit_homework.student_id');

            if (!empty($request->student_first_name)) {
                $return = $return->where('users.name', 'like', '%' . $request->student_first_name . '%');
            }

            if (!empty($request->student_last_name)) {
                $return = $return->where('users.last_name', 'like', '%' . $request->student_last_name . '%');
            }

            if (!empty($request->from_submitted_date)) {
                $return = $return->whereDate('submit_homework.created_at', '>=', $request->from_submitted_date);
            }

            if (!empty($request->to_submitted_date)) {
                $return = $return->whereDate('submit_homework.created_at', '<=', $request->to_submitted_date);
            }

            $return = $return->where('submit_homework.homework_id', '=', $homework_id)
                ->orderBy('submit_homework.id', 'desc')->paginate(20);

            $data['getRecord'] = $return;
            $data['homework_id'] = $homework_id;
            $data['header_title'] = 'Student Submitted Homework';
            if (Auth::user()->user_type == 1) {
                return view('admin.homework.submitted', $data);
            } elseif (Auth::user()->user_type == 2) {
                return view('teacher.homework.submitted', $data);
            }
        } else {

            if (Auth::user()->user_type == 1) {
                return redirect('admin/homework/homework')->with('success', 'Homework submitted Not Found');
            } elseif (Auth::user()->user_type == 2) {
                return redirect('teacher/homework/homework')->with('success', 'Homework submitted Not Found');
            }
        }
    }

    //Teacher side
    public function HomeworkTeacher(Request $request)
    {
        $class_ids = array();
        $getClass = AssignClassTeacher::select('assign_class_teacher.*', 'class.name as class_name', 'class.id as class_id',)
            ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
            ->where('assign_class_teacher.is_delete', '=', 0)
            ->where('assign_class_teacher.status', '=', 0)
            ->where('assign_class_teacher.teacher_id', '=', Auth::user()->id)
            ->groupBy('assign_class_teacher.class_id')->get();

        foreach ($getClass as $class) {
            $class_ids[] = $class->class_id;
        }

        $return = Homework::select('homework.*', 'class.name as class_name', 'subject.name as subject_name', 'users.name as created_by_name')
            ->join('class', 'class.id', 'homework.class_id')
            ->join('subject', 'subject.id', 'homework.subject_id')
            ->join('users', 'users.id', 'homework.created_by')
            ->whereIn('homework.class_id', $class_ids);

        if (!empty($request->class)) {
            $return = $return->where('class.name', 'like', '%' . $request->class . '%');
        }

        if (!empty($request->subject)) {
            $return = $return->where('subject.name', 'like', '%' . $request->subject . '%');
        }

        if (!empty($request->from_homework_date)) {
            $return = $return->whereDate('homework.homework_date', '>=', $request->from_homework_date);
        }

        if (!empty($request->to_homework_date)) {
            $return = $return->whereDate('homework.homework_date', '<=', $request->to_homework_date);
        }

        if (!empty($request->from_submission_date)) {
            $return = $return->whereDate('homework.submission_date', '>=', $request->from_submission_date);
        }

        if (!empty($request->to_submission_date)) {
            $return = $return->whereDate('homework.submission_date', '<=', $request->to_submission_date);
        }

        $return = $return->where('homework.is_delete', '=', 0)
            ->orderBy('homework.id', 'desc')->paginate(10);

        $data['getRecord'] = $return;
        $data['header_title'] = 'Homework';
        return view('teacher.homework.list', $data);
    }

    //Student Side
    public function HomeworkStudent(Request $request)
    {
        $student_id = Auth::user()->id;
        $return = Homework::select('homework.*', 'class.name as class_name', 'subject.name as subject_name', 'users.name as created_by_name')
            ->join('class', 'class.id', 'homework.class_id')
            ->join('subject', 'subject.id', 'homework.subject_id')
            ->join('users', 'users.id', 'homework.created_by')
            ->where('homework.class_id', '=', Auth::user()->class_id)
            ->whereNotIn('homework.id', function ($query) use ($student_id) {
                $query->select('submit_homework.homework_id')
                    ->from('submit_homework')
                    ->where('submit_homework.student_id', '=', $student_id);
            });

        if (!empty($request->subject)) {
            $return = $return->where('subject.name', 'like', '%' . $request->subject . '%');
        }

        if (!empty($request->from_homework_date)) {
            $return = $return->whereDate('homework.homework_date', '>=', $request->from_homework_date);
        }

        if (!empty($request->to_homework_date)) {
            $return = $return->whereDate('homework.homework_date', '<=', $request->to_homework_date);
        }

        if (!empty($request->from_submission_date)) {
            $return = $return->whereDate('homework.submission_date', '>=', $request->from_submission_date);
        }

        if (!empty($request->to_submission_date)) {
            $return = $return->whereDate('homework.submission_date', '<=', $request->to_submission_date);
        }

        $return = $return->where('homework.is_delete', '=', 0)
            ->orderBy('homework.id', 'desc')->paginate(10);

        $data['getRecord'] = $return;
        $data['header_title'] = 'My Homework';
        return view('student.homework.list', $data);
    }

    public function SubmitHomework($homework_id)
    {
        $data['getRecord'] = Homework::find($homework_id);
        $data['header_title'] = 'Submit Homework';
        return view('student.homework.submit', $data);
    }

    public function InsertSubmitHomework(Request $request, $homework_id)
    {
        $validator = Validator::make($request->all(), [
            'document' => 'required',
            // 'description' => 'required',
        ]);

        if ($validator->passes()) {

            $homework = new SubmitHomework;
            $homework->homework_id = $homework_id;
            $homework->student_id = Auth::user()->id;

            if (!empty($request->file('document'))) {

                $ext = $request->file('document')->getClientOriginalExtension();
                $file = $request->file('document');
                $randomStr = Str::random(20);
                $filename = strtolower($randomStr) . '.' . $ext;
                $file->move(public_path() . '/uploads/homework/', $filename);
                $homework->document = $filename;
            }

            $homework->description = $request->description;
            $homework->save();

            return redirect('student/my_homework')->with('success', 'Homework submitted successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function SubmittedHomework(Request $request)
    {
        $return = SubmitHomework::select('submit_homework.*', 'class.name as class_name', 'subject.name as subject_name')
            ->join('homework', 'homework.id', 'submit_homework.homework_id')
            ->join('class', 'class.id', 'homework.class_id')
            ->join('subject', 'subject.id', 'homework.subject_id');

        if (!empty($request->subject)) {
            $return = $return->where('subject.name', 'like', '%' . $request->subject . '%');
        }

        if (!empty($request->from_homework_date)) {
            $return = $return->whereDate('homework.homework_date', '>=', $request->from_homework_date);
        }

        if (!empty($request->to_homework_date)) {
            $return = $return->whereDate('homework.homework_date', '<=', $request->to_homework_date);
        }

        if (!empty($request->from_submission_date)) {
            $return = $return->whereDate('homework.submission_date', '>=', $request->from_submission_date);
        }

        if (!empty($request->to_submission_date)) {
            $return = $return->whereDate('homework.submission_date', '<=', $request->to_submission_date);
        }

        if (!empty($request->from_submitted_date)) {
            $return = $return->whereDate('submit_homework.created_at', '>=', $request->from_submitted_date);
        }

        if (!empty($request->to_submitted_date)) {
            $return = $return->whereDate('submit_homework.created_at', '<=', $request->to_submitted_date);
        }

        $return = $return->where('submit_homework.student_id', '=', Auth::user()->id)
            ->orderBy('submit_homework.id', 'desc')->paginate(10);

        $data['getRecord'] = $return;
        $data['header_title'] = 'My Submitted Homework';
        return view('student.homework.submittedlist', $data);
    }

    //Parent Side
    public function HomeworkParent($student_id, Request $request)
    {
        $getStudent = User::find($student_id);
        $return = Homework::select('homework.*', 'class.name as class_name', 'subject.name as subject_name', 'users.name as created_by_name')
            ->join('class', 'class.id', 'homework.class_id')
            ->join('subject', 'subject.id', 'homework.subject_id')
            ->join('users', 'users.id', 'homework.created_by')
            ->where('homework.class_id', '=', $getStudent->class_id)
            ->whereNotIn('homework.id', function ($query) use ($student_id) {
                $query->select('submit_homework.homework_id')
                    ->from('submit_homework')
                    ->where('submit_homework.student_id', '=', $student_id);
            });

        if (!empty($request->subject)) {
            $return = $return->where('subject.name', 'like', '%' . $request->subject . '%');
        }

        if (!empty($request->from_homework_date)) {
            $return = $return->whereDate('homework.homework_date', '>=', $request->from_homework_date);
        }

        if (!empty($request->to_homework_date)) {
            $return = $return->whereDate('homework.homework_date', '<=', $request->to_homework_date);
        }

        if (!empty($request->from_submission_date)) {
            $return = $return->whereDate('homework.submission_date', '>=', $request->from_submission_date);
        }

        if (!empty($request->to_submission_date)) {
            $return = $return->whereDate('homework.submission_date', '<=', $request->to_submission_date);
        }

        $return = $return->where('homework.is_delete', '=', 0)
            ->orderBy('homework.id', 'desc')->paginate(10);

        $data['getRecord'] = $return;
        $data['getStudent'] = $getStudent;
        $data['header_title'] = 'Student Homework';
        return view('parent.homework.list', $data);
    }

    public function SubmittedHomeworkParent($student_id, Request $request)
    {
        $getStudent = User::find($student_id);
        $return = SubmitHomework::select('submit_homework.*', 'class.name as class_name', 'subject.name as subject_name')
            ->join('homework', 'homework.id', 'submit_homework.homework_id')
            ->join('class', 'class.id', 'homework.class_id')
            ->join('subject', 'subject.id', 'homework.subject_id');

        if (!empty($request->subject)) {
            $return = $return->where('subject.name', 'like', '%' . $request->subject . '%');
        }

        if (!empty($request->from_homework_date)) {
            $return = $return->whereDate('homework.homework_date', '>=', $request->from_homework_date);
        }

        if (!empty($request->to_homework_date)) {
            $return = $return->whereDate('homework.homework_date', '<=', $request->to_homework_date);
        }

        if (!empty($request->from_submission_date)) {
            $return = $return->whereDate('homework.submission_date', '>=', $request->from_submission_date);
        }

        if (!empty($request->to_submission_date)) {
            $return = $return->whereDate('homework.submission_date', '<=', $request->to_submission_date);
        }

        if (!empty($request->from_submitted_date)) {
            $return = $return->whereDate('submit_homework.created_at', '>=', $request->from_submitted_date);
        }

        if (!empty($request->to_submitted_date)) {
            $return = $return->whereDate('submit_homework.created_at', '<=', $request->to_submitted_date);
        }

        $return = $return->where('submit_homework.student_id', '=', $getStudent->id)
            ->orderBy('submit_homework.id', 'desc')->paginate(10);

        $data['getRecord'] = $return;
        $data['getStudent'] = $getStudent;
        $data['header_title'] = 'Student Submitted Homework';
        return view('parent.homework.submittedlist', $data);
    }
}
