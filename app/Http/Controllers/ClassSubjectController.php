<?php

namespace App\Http\Controllers;

use App\Models\ClassSubject;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClassSubjectController extends Controller
{
    public function list(Request $request)
    {
        $return = ClassSubject::select('class_subject.*', 'class.name as class_name', 'subject.name as subject_name', 'users.name as created_by_name')
            ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
            ->join('class', 'class.id', '=', 'class_subject.class_id')
            ->join('users', 'users.id', '=', 'class_subject.created_by');

        if (!empty($request->class_name)) {
            $return = $return->where('class.name', 'LIKE', '%' . $request->class_name . '%');
        }
        if (!empty($request->subject_name)) {
            $return = $return->where('subject.name', 'LIKE', '%' .  $request->subject_name . '%');
        }
        if (!empty($request->date)) {
            $return = $return->whereDate('class_subject.created_at', '=', $request->date);
        }

        $return =  $return->where('class_subject.is_delete', '=', 0)
            ->orderBy('class_subject.id', 'desc')
            ->paginate(2);
        $data['getRecord'] = $return;
        $data['header_title'] = 'Assign Subject list';
        return view('admin.assign_subject.list', $data);
    }

    public function add()
    {
        $data['getClass'] = ClassModel::select('class.*')
            ->join('users', 'users.id', 'class.created_by')
            ->where('class.is_delete', '=', 0)
            ->where('class.status', '=', 0)
            ->orderBy('class.name', 'asc')
            ->get();
        $data['getSubject'] = Subject::select('subject.*')
            ->join('users', 'users.id', 'subject.created_by')
            ->where('subject.is_delete', '=', 0)
            ->where('subject.status', '=', 0)
            ->orderBy('subject.name', 'asc')
            ->get();
        $data['header_title'] = 'Add Assign Subject';
        return view('admin.assign_subject.add', $data);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'subject_id' => 'required',
        ]);

        if ($validator->passes()) {

            if (!empty($request->subject_id)) {
                foreach ($request->subject_id as $subject_id) {

                    $count = ClassSubject::where('class_id', '=', $request->class_id)
                        ->where('subject_id', '=', $subject_id)->first();
                    if (!empty($count)) {
                        $count->status = $request->status;
                        $count->save();
                    } else {
                        $class_subject = new ClassSubject;
                        $class_subject->class_id = $request->class_id;
                        $class_subject->subject_id = $subject_id;
                        $class_subject->status = $request->status;
                        $class_subject->created_by = Auth::user()->id;
                        $class_subject->save();
                    }
                }

                return redirect('admin/assign_subject/list')->with('success', 'Subject Assign to Class successfully');
            }
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function edit($id)
    {
        $getRecord = ClassSubject::find($id);
        if (!empty($getRecord)) {

            $data['getRecord'] = $getRecord;
            $data['getAssignedSubject'] = ClassSubject::where('class_id', '=', $getRecord->class_id)
                ->where('is_delete', '=', 0)->get();

            $data['getClass'] = ClassModel::select('class.*')
                ->join('users', 'users.id', 'class.created_by')
                ->where('class.is_delete', '=', 0)
                ->where('class.status', '=', 0)
                ->orderBy('class.name', 'asc')
                ->get();
            $data['getSubject'] = Subject::select('subject.*')
                ->join('users', 'users.id', 'subject.created_by')
                ->where('subject.is_delete', '=', 0)
                ->where('subject.status', '=', 0)
                ->orderBy('subject.name', 'asc')
                ->get();
            $data['header_title'] = 'Edit Assign Subject';
            return view('admin.assign_subject.edit', $data);
        } else {
            return redirect()->back()->with('error', 'Subject not found');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'subject_id' => 'required',
        ]);

        if ($validator->passes()) {


            ClassSubject::where('class_id', '=', $request->class_id)->delete();

            if (!empty($request->subject_id)) {
                foreach ($request->subject_id as $subject_id) {

                    $count = ClassSubject::where('class_id', '=', $request->class_id)
                        ->where('subject_id', '=', $subject_id)->first();
                    if (!empty($count)) {
                        $count->status = $request->status;
                        $count->save();
                    } else {
                        $class_subject = new ClassSubject;
                        $class_subject->class_id = $request->class_id;
                        $class_subject->subject_id = $subject_id;
                        $class_subject->status = $request->status;
                        $class_subject->created_by = Auth::user()->id;
                        $class_subject->save();
                    }
                }
            }

            return redirect('admin/assign_subject/list')->with('success', 'Subject Assign to Class successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function delete($id)
    {
        $class_subject = ClassSubject::find($id);
        $class_subject->is_delete = 1;
        $class_subject->save();
        return redirect('admin/assign_subject/list')->with('success', 'Subject deleted successfully');
    }

    public function edit_single($id)
    {
        $getRecord = ClassSubject::find($id);
        if (!empty($getRecord)) {

            $data['getRecord'] = $getRecord;

            $data['getClass'] = ClassModel::select('class.*')
                ->join('users', 'users.id', 'class.created_by')
                ->where('class.is_delete', '=', 0)
                ->where('class.status', '=', 0)
                ->orderBy('class.name', 'asc')
                ->get();
            $data['getSubject'] = Subject::select('subject.*')
                ->join('users', 'users.id', 'subject.created_by')
                ->where('subject.is_delete', '=', 0)
                ->where('subject.status', '=', 0)
                ->orderBy('subject.name', 'asc')
                ->get();
            $data['header_title'] = 'Edit Assign Subject';
            return view('admin.assign_subject.edit_single', $data);
        } else {
            return redirect()->back()->with('error', 'Subject not found');
        }
    }

    public function update_single(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'subject_id' => 'required',
        ]);

        if ($validator->passes()) {

            $count = ClassSubject::where('class_id', '=', $request->class_id)
                ->where('subject_id', '=', $request->subject_id)->first();
            if (!empty($count)) {
                $count->status = $request->status;
                $count->save();

                return redirect('admin/assign_subject/list')->with('success', 'Status updated successfully');
            } else {
                $class_subject = ClassSubject::find($id);;
                $class_subject->class_id = $request->class_id;
                $class_subject->subject_id = $request->subject_id;
                $class_subject->status = $request->status;
                $class_subject->save();

                return redirect('admin/assign_subject/list')->with('success', 'Subject Assign to Class successfully');
            }
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
}
