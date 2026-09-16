<?php

namespace App\Http\Controllers;

use App\Models\AssignClassTeacher;
use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AssignClassTeacherController extends Controller
{
    public function list(Request $request)
    {
        $return = AssignClassTeacher::select('assign_class_teacher.*', 'class.name as class_name', 'teacher.name as teacher_name', 'teacher.last_name as teacher_last_name', 'users.name as created_by_name')
            ->join('users as teacher', 'teacher.id', '=',  'assign_class_teacher.teacher_id')
            ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
            ->join('users', 'users.id', '=',  'assign_class_teacher.created_by');

        if (!empty($request->class_name)) {
            $return = $return->where('class.name', 'LIKE', '%' . $request->class_name . '%');
        }
        if (!empty($request->teacher_name)) {
            $return = $return->where('teacher.name', 'LIKE', '%' .  $request->teacher_name . '%');
        }
        if (!empty($request->date)) {
            $return = $return->whereDate('assign_class_teacher.created_at', '=', $request->date);
        }

        $return =  $return->where('assign_class_teacher.is_delete', '=', 0)
            ->orderBy('assign_class_teacher.id', 'desc')
            ->paginate(2);
        $data['getRecord'] = $return;
        $data['header_title'] = 'Assign Class Teacher';
        return view('admin.assign_class_teacher.list', $data);
    }

    public function add()
    {
        $data['getClass'] = ClassModel::select('class.*')
            ->join('users', 'users.id', 'class.created_by')
            ->where('class.is_delete', '=', 0)
            ->where('class.status', '=', 0)
            ->orderBy('class.name', 'asc')
            ->get();
        $data['getTeacher'] = User::select('users.*')
            ->where('users.is_delete', '=', 0)
            ->where('users.user_type', '=', 2)
            ->orderBy('users.id', 'desc')
            ->get();
        $data['header_title'] = 'Add Assign Class Teacher';
        return view('admin.assign_class_teacher.add', $data);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'teacher_id' => 'required',
        ]);

        if ($validator->passes()) {

            if (!empty($request->teacher_id)) {
                foreach ($request->teacher_id as $teacher_id) {

                    $count = AssignClassTeacher::where('class_id', '=', $request->class_id)
                        ->where('teacher_id', '=', $teacher_id)->first();
                    if (!empty($count)) {
                        $count->status = $request->status;
                        $count->save();
                    } else {
                        $assign_class_teacher = new AssignClassTeacher;
                        $assign_class_teacher->class_id = $request->class_id;
                        $assign_class_teacher->teacher_id = $teacher_id;
                        $assign_class_teacher->status = $request->status;
                        $assign_class_teacher->created_by = Auth::user()->id;
                        $assign_class_teacher->save();
                    }
                }

                return redirect('admin/assign_class_teacher/list')->with('success', 'Teacher Assign to Class successfully');
            }
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function edit($id)
    {
        $getRecord = AssignClassTeacher::find($id);
        if (!empty($getRecord)) {

            $data['getRecord'] = $getRecord;
            $data['getAssignedTeacher'] = AssignClassTeacher::where('class_id', '=', $getRecord->class_id)
                ->where('is_delete', '=', 0)->get();

            $data['getClass'] = ClassModel::select('class.*')
                ->join('users', 'users.id', 'class.created_by')
                ->where('class.is_delete', '=', 0)
                ->where('class.status', '=', 0)
                ->orderBy('class.name', 'asc')
                ->get();
            $data['getTeacher'] = User::select('users.*')
                ->where('users.is_delete', '=', 0)
                ->where('users.user_type', '=', 2)
                ->orderBy('users.id', 'desc')
                ->get();
            $data['header_title'] = 'Edit Assign Class Teacher';
            return view('admin.assign_class_teacher.edit', $data);
        } else {
            return redirect('admin/assign_class_teacher/list')->with('error', 'Teacher not found');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'teacher_id' => 'required',
        ]);

        if ($validator->passes()) {

            AssignClassTeacher::where('class_id', '=', $request->class_id)->delete();

            if (!empty($request->teacher_id)) {
                foreach ($request->teacher_id as $teacher_id) {

                    $count = AssignClassTeacher::where('class_id', '=', $request->class_id)
                        ->where('teacher_id', '=', $teacher_id)->first();
                    if (!empty($count)) {
                        $count->status = $request->status;
                        $count->save();
                    } else {
                        $assign_class_teacher = new AssignClassTeacher;
                        $assign_class_teacher->class_id = $request->class_id;
                        $assign_class_teacher->teacher_id = $teacher_id;
                        $assign_class_teacher->status = $request->status;
                        $assign_class_teacher->created_by = Auth::user()->id;
                        $assign_class_teacher->save();
                    }
                }
            }

            return redirect('admin/assign_class_teacher/list')->with('success', 'Teacher Assign to Class successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function delete($id)
    {
        $assign_class_teacher = AssignClassTeacher::find($id);
        $assign_class_teacher->is_delete = 1;
        $assign_class_teacher->save();
        return redirect('admin/assign_class_teacher/list')->with('success', 'Teacher deleted successfully');
    }

    public function edit_single($id)
    {
        $getRecord = AssignClassTeacher::find($id);
        if (!empty($getRecord)) {

            $data['getRecord'] = $getRecord;

            $data['getClass'] = ClassModel::select('class.*')
                ->join('users', 'users.id', 'class.created_by')
                ->where('class.is_delete', '=', 0)
                ->where('class.status', '=', 0)
                ->orderBy('class.name', 'asc')
                ->get();
            $data['getTeacher'] = User::select('users.*')
                ->where('users.is_delete', '=', 0)
                ->where('users.user_type', '=', 2)
                ->orderBy('users.id', 'desc')
                ->get();
            $data['header_title'] = 'Edit Assign Class Teacher';
            return view('admin.assign_class_teacher.edit_single', $data);
        } else {
            return redirect()->back()->with('error', 'Teacher not found');
        }
    }

    public function update_single(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'teacher_id' => 'required',
        ]);

        if ($validator->passes()) {

            $count = AssignClassTeacher::where('class_id', '=', $request->class_id)
                ->where('teacher_id', '=', $request->teacher_id)->first();
            if (!empty($count)) {
                $count->status = $request->status;
                $count->save();

                return redirect('admin/assign_class_teacher/list')->with('success', 'Status updated successfully');
            } else {
                $assign_class_teacher = AssignClassTeacher::find($id);;
                $assign_class_teacher->class_id = $request->class_id;
                $assign_class_teacher->subject_id = $request->subject_id;
                $assign_class_teacher->status = $request->status;
                $assign_class_teacher->save();

                return redirect('admin/assign_class_teacher/list')->with('success', 'Subject Assign to Class successfully Updated');
            }
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    //Teacher Side
    public function myClassSubject()
    {
        $data['getRecord'] = AssignClassTeacher::select('assign_class_teacher.*',
        'class.name as class_name','subject.name as subject_name','subject.type as subject_type',
        'class.id as class_id', 'subject.id as subject_id')
            ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
            ->join('class_subject', 'class_subject.class_id', '=', 'class.id')
            ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
            ->where('assign_class_teacher.is_delete', '=', 0)
            ->where('assign_class_teacher.status', '=', 0)
            ->where('subject.is_delete', '=', 0)
            ->where('subject.status', '=', 0)
            ->where('class_subject.is_delete', '=', 0)
            ->where('class_subject.status', '=', 0)
            ->where('assign_class_teacher.teacher_id', '=', Auth::user()->id)
            ->orderBy('assign_class_teacher.id', 'desc')
            ->get();
        $data['header_title'] = 'My Class & Subject';
        return view('teacher.myclasssubject', $data);
    }
}
