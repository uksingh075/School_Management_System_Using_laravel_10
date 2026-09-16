<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function list(Request $request)
    {
        $return = User::select('users.*')
            ->where('users.user_type', '=', 2)
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
        if (!empty($request->gender)) {
            $return = $return->where('users.gender', '=',  $request->gender);
        }
        if (!empty($request->mobile_number)) {
            $return = $return->where('users.mobile_number', 'LIKE', '%' . $request->mobile_number . '%');
        }
        if (!empty($request->marital_status)) {
            $return = $return->where('users.marital_status', 'LIKE', '%' . $request->marital_status . '%');
        }
        if (!empty($request->address)) {
            $return = $return->where('users.address', 'LIKE', '%' . $request->address . '%');
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
        $data['header_title'] = 'Teacher list';
        return view('admin.teacher.list', $data);
    }

    public function add()
    {
        $data['header_title'] = 'Add New Teacher';
        return view('admin.teacher.add', $data);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'admission_date' => 'required',
            'mobile_number' => 'required|min:10|max:10|regex:/^[0-9]+$/',
            'marital_status' => 'required',
            'address' => 'required',
            'permanent_address' => 'required',
            'qualification' => 'required',
            'work_experience' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->passes()) {

            $teacher = new User;
            $teacher->name = $request->name;
            $teacher->last_name = $request->last_name;
            $teacher->gender = $request->gender;

            if (!empty($request->date_of_birth)) {
                $teacher->date_of_birth = $request->date_of_birth;
            }

            if (!empty($request->admission_date)) {
                $teacher->admission_date = $request->admission_date;
            }

            $teacher->mobile_number = $request->mobile_number;
            $teacher->marital_status = $request->marital_status;

            if (!empty($request->file('profile_pic'))) {

                $ext = $request->file('profile_pic')->getClientOriginalExtension();
                $file = $request->file('profile_pic');
                $randomStr = Str::random(20);
                $filename = strtolower($randomStr) . '.' . $ext;
                $file->move(public_path() . '/uploads/profile/', $filename);

                $teacher->profile_pic = $filename;
            }

            $teacher->address = $request->address;
            $teacher->permanent_address = $request->permanent_address;
            $teacher->qualification = $request->qualification;
            $teacher->work_experience = $request->work_experience;
            $teacher->status = $request->status;
            $teacher->email = $request->email;
            $teacher->password = Hash::make($request->password);
            $teacher->user_type = 2;
            $teacher->save();

            return redirect('admin/teacher/list')->with('success', 'Teacher added successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function edit($id)
    {
        $data['getRecord'] = User::find($id);
        if (!empty($data['getRecord'])) {

            $data['header_title'] = 'Edit Teacher';
            return view('admin.teacher.edit', $data);
        } else {
            return redirect()->back()->with('error', 'Teacher not found');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'admission_date' => 'required',
            'mobile_number' => 'required|min:10|max:10|regex:/^[0-9]+$/',
            'marital_status' => 'required',
            'address' => 'required',
            'permanent_address' => 'required',
            'qualification' => 'required',
            'work_experience' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->passes()) {

            $teacher = User::find($id);
            $teacher->name = $request->name;
            $teacher->last_name = $request->last_name;
            $teacher->gender = $request->gender;

            if (!empty($request->date_of_birth)) {
                $teacher->date_of_birth = $request->date_of_birth;
            }

            if (!empty($request->admission_date)) {
                $teacher->admission_date = $request->admission_date;
            }

            $teacher->mobile_number = $request->mobile_number;
            $teacher->marital_status = $request->marital_status;

            if (!empty($request->file('profile_pic'))) {

                if (!empty($teacher->getProfile())) {
                    unlink('uploads/profile/' . $teacher->profile_pic);
                }
                $ext = $request->file('profile_pic')->getClientOriginalExtension();
                $file = $request->file('profile_pic');
                $randomStr = Str::random(20);
                $filename = strtolower($randomStr) . '.' . $ext;
                $file->move(public_path() . '/uploads/profile/', $filename);
                $teacher->profile_pic = $filename;
            }

            $teacher->address = $request->address;
            $teacher->permanent_address = $request->permanent_address;
            $teacher->qualification = $request->qualification;
            $teacher->work_experience = $request->work_experience;
            $teacher->status = $request->status;
            $teacher->email = $request->email;

            if (!empty($request->password)) {
                $teacher->password = Hash::make($request->password);
            }

            $teacher->save();

            return redirect('admin/teacher/list')->with('success', 'Teacher updated successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function delete($id)
    {
        $teacher = User::find($id);
        $teacher->is_delete = 1;
        $teacher->save();

        return redirect('admin/teacher/list')->with('success', 'Teacher deleted successfully');
    }
}
