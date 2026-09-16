<?php

namespace App\Http\Controllers;

use App\Models\ClassSubject;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function list(Request $request)
    {
        $return = Subject::select('subject.*', 'users.name as created_by_name')
            ->join('users', 'users.id', 'subject.created_by');

        if (!empty($request->name)) {
            $return = $return->where('subject.name', 'LIKE', '%' . $request->name . '%');
        }
        if (!empty($request->type)) {
            $return = $return->where('subject.type', '=',  $request->type);
        }
        if (!empty($request->date)) {
            $return = $return->whereDate('subject.created_at', '=', $request->date);
        }

        $return =  $return->where('subject.is_delete', '=', 0)
            ->where('subject.status', '=', 0)
            ->orderBy('subject.id', 'desc')
            ->paginate(2);
        $data['getRecord'] = $return;
        $data['header_title'] = 'Subject list';
        return view('admin.subject.list', $data);
    }

    public function add()
    {
        $data['header_title'] = 'Add Subject';
        return view('admin.subject.add', $data);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|regex:/^[a-zA-Z\s]+$/',
            'type' => 'required',
        ]);

        if ($validator->passes()) {
            $subject = new Subject;
            $subject->name = $request->name;
            $subject->type = $request->type;
            $subject->status = $request->status;
            $subject->created_by = Auth::user()->id;
            $subject->save();

            return redirect('admin/subject/list')->with('success', 'Subject added successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function edit($id)
    {
        $data['getRecord'] = Subject::find($id);
        if (!empty($data['getRecord'])) {
            $data['header_title'] = 'Edit Subject';
            return view('admin.subject.edit', $data);
        } else {
            return redirect()->back()->with('error', 'Subject not found');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|regex:/^[a-zA-Z\s]+$/',
            'type' => 'required',
        ]);

        if ($validator->passes()) {

            $subject = Subject::find($id);
            $subject->name = $request->name;
            $subject->type = $request->type;
            $subject->status = $request->status;
            $subject->save();

            return redirect('admin/subject/list')->with('success', 'Subject updated successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function delete($id)
    {
        $class = Subject::find($id);
        $class->is_delete = 1;
        $class->save();
        return redirect('admin/subject/list')->with('success', 'Subject deleted successfully');
    }

    public function mySubject()
    {
        $return = ClassSubject::select('class_subject.*', 'subject.name as subject_name', 'subject.type as subject_type')
            ->join('class', 'class.id', '=', 'class_subject.class_id')
            ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
            ->join('users', 'users.id', '=', 'class_subject.created_by')
            ->where('class_subject.class_id', '=', Auth::user()->class_id)
            ->where('class_subject.is_delete', '=', 0)
            ->where('class_subject.status', '=', 0)
            ->orderBy('class_subject.id', 'desc')
            ->get();
        $data['getRecord'] = $return;
        $data['header_title'] = 'My Subject';
        return view('student.mysubject', $data);
    }

    public function ParentStudentSubject($student_id)
    {
        $user = User::find($student_id);
        $return = ClassSubject::select('class_subject.*', 'subject.name as subject_name', 'subject.type as subject_type')
            ->join('class', 'class.id', 'class_subject.class_id')
            ->join('subject', 'subject.id', 'class_subject.subject_id')
            ->join('users', 'users.id', 'class_subject.created_by')
            ->where('class_subject.class_id', '=', $user->class_id)
            ->where('class_subject.is_delete', '=', 0)
            ->where('class_subject.status', '=', 0)
            ->orderBy('class_subject.id', 'asc')
            ->get();
        $data['getUser'] = $user;
        $data['getRecord'] = $return;
        $data['header_title'] = 'Student Subject';
        return view('parent.mystudentsubject', $data);
    }
}
