<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function list(Request $request)
    {
        $return = User::select('users.*', 'class.name as class_name', 'parent.name as parent_name', 'parent.last_name as parent_last_name')
            ->join('users as parent', 'parent.id', '=', 'users.parent_id', 'left')
            ->join('class', 'class.id', '=', 'users.class_id', 'left')
            ->where('users.user_type', '=', 3)
            ->where('users.is_delete', '=', 0);
        if (!empty($request->name)) {
            $return = $return->where('users.name', 'LIKE', '%' . $request->name . '%');
        }
        if (!empty($request->last_name)) {
            $return = $return->where('users.last_name', 'LIKE', '%' . $request->last_name . '%');
        }
        if (!empty($request->email)) {
            $return = $return->where('users.email', 'LIKE', '%' . $request->email . '%');
        }
        if (!empty($request->admission_number)) {
            $return = $return->where('users.admission_number', 'LIKE', '%' . $request->admission_number . '%');
        }
        if (!empty($request->roll_number)) {
            $return = $return->where('users.roll_number', 'LIKE', '%' . $request->roll_number . '%');
        }
        if (!empty($request->class)) {
            $return = $return->where('class.name', 'LIKE', '%' . $request->class . '%');
        }
        if (!empty($request->gender)) {
            $return = $return->where('users.gender', '=',  $request->gender);
        }
        if (!empty($request->mobile_number)) {
            $return = $return->where('users.mobile_number', 'LIKE', '%' . $request->mobile_number . '%');
        }
        if (!empty($request->blood_group)) {
            $return = $return->where('users.blood_group', 'LIKE', '%' . $request->blood_group . '%');
        }
        if (!empty($request->admission_date)) {
            $return = $return->whereDate('users.admission_date', '=', $request->admission_date);
        }
        if (!empty($request->date)) {
            $return = $return->whereDate('users.created_at', '=', $request->date);
        }
        if (!empty($request->status)) {
            $status = $request->status == 100 ? 0 : 1;
            $return = $return->where('users.status', '=', $status);
        }
        $return = $return->orderBy('users.id', 'desc')
            ->paginate(3);
        $data['getRecord'] = $return;
        $data['header_title'] = 'Student list';
        return view('admin.student.list', $data);
    }

    public function add()
    {
        $data['getClass'] = ClassModel::select('class.*')
            ->join('users', 'users.id', 'class.created_by')
            ->where('class.is_delete', '=', 0)
            ->where('class.status', '=', 0)
            ->orderBy('class.name', 'asc')
            ->get();
        $data['header_title'] = 'Add New Student';
        return view('admin.student.add', $data);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'admission_number' => 'required',
            'roll_number' => 'required',
            'class_id' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'mobile_number' => 'required|min:10|max:10|regex:/^[0-9]+$/',
            'admission_date' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->passes()) {

            $student = new User;
            $student->name = $request->name;
            $student->last_name = $request->last_name;
            $student->admission_number = $request->admission_number;
            $student->roll_number = $request->roll_number;
            $student->class_id = $request->class_id;
            $student->gender = $request->gender;

            if (!empty($request->date_of_birth)) {
                $student->date_of_birth = $request->date_of_birth;
            }

            $student->mobile_number = $request->mobile_number;

            if (!empty($request->admission_date)) {
                $student->admission_date = $request->admission_date;
            }

            if (!empty($request->file('profile_pic'))) {

                $ext = $request->file('profile_pic')->getClientOriginalExtension();
                $file = $request->file('profile_pic');
                $randomStr = Str::random(20);
                $filename = strtolower($randomStr) . '.' . $ext;
                $file->move(public_path() . '/uploads/profile/', $filename);

                $student->profile_pic = $filename;
            }

            $student->blood_group = $request->blood_group;
            $student->height = $request->height;
            $student->weight = $request->weight;
            $student->status = $request->status;
            $student->email = $request->email;
            $student->password = Hash::make($request->password);
            $student->user_type = 3;
            $student->save();

            return redirect('admin/student/list')->with('success', 'Student added successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function edit($id)
    {
        $data['getRecord'] = User::find($id);
        if (!empty($data['getRecord'])) {

            $data['getClass'] = ClassModel::select('class.*')
                ->join('users', 'users.id', 'class.created_by')
                ->where('class.is_delete', '=', 0)
                ->where('class.status', '=', 0)
                ->orderBy('class.name', 'asc')
                ->get();
            $data['header_title'] = 'Edit Student';
            return view('admin.student.edit', $data);
        } else {
            return redirect()->back()->with('error', 'Student not found');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'admission_number' => 'required',
            'roll_number' => 'required',
            'class_id' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'mobile_number' => 'required|min:10|max:10|regex:/^[0-9]+$/',
            'admission_date' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->passes()) {

            $student = User::find($id);
            $student->name = $request->name;
            $student->last_name = $request->last_name;
            $student->admission_number = $request->admission_number;
            $student->roll_number = $request->roll_number;
            $student->class_id = $request->class_id;
            $student->gender = $request->gender;

            if (!empty($request->date_of_birth)) {
                $student->date_of_birth = $request->date_of_birth;
            }

            $student->mobile_number = $request->mobile_number;

            if (!empty($request->admission_date)) {
                $student->admission_date = $request->admission_date;
            }

            if (!empty($request->file('profile_pic'))) {

                if (!empty($student->getProfile())) {
                    unlink('uploads/profile/' . $student->profile_pic);
                }
                $ext = $request->file('profile_pic')->getClientOriginalExtension();
                $file = $request->file('profile_pic');
                $randomStr = Str::random(20);
                $filename = strtolower($randomStr) . '.' . $ext;
                $file->move(public_path() . '/uploads/profile/', $filename);
                $student->profile_pic = $filename;
            }

            $student->blood_group = $request->blood_group;
            $student->height = $request->height;
            $student->weight = $request->weight;
            $student->status = $request->status;
            $student->email = $request->email;

            if (!empty($request->password)) {
                $student->password = Hash::make($request->password);
            }

            $student->save();

            return redirect('admin/student/list')->with('success', 'Student updated successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function delete($id)
    {
        $student = User::find($id);
        $student->is_delete = 1;
        $student->save();

        return redirect('admin/student/list')->with('success', 'Student deleted successfully');
    }

    //Teacher Side
    public function myStudent()
    {
        $data['getRecord'] = User::select('users.*', 'class.name as class_name')
            ->join('class', 'class.id', '=', 'users.class_id')
            ->join('assign_class_teacher', 'assign_class_teacher.class_id', '=', 'class.id')
            ->where('assign_class_teacher.teacher_id', '=', Auth::user()->id)
            ->where('assign_class_teacher.status', '=', 0)
            ->where('assign_class_teacher.is_delete', '=', 0)
            ->where('users.user_type', '=', 3)
            ->where('users.is_delete', '=', 0)
            ->where('users.status', '=', 0)
            ->orderBy('users.id', 'desc')
            ->paginate(5);
        $data['header_title'] = 'Student list';
        return view('teacher.mystudent', $data);
    }
}
