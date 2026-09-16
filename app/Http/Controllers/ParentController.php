<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ParentController extends Controller
{
    public function list(Request $request)
    {
        $return = User::select('users.*', 'class.name as class_name')
            ->join('class', 'class.id', '=', 'users.class_id', 'left')
            ->where('users.user_type', '=', 4)
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
        if (!empty($request->occupation)) {
            $return = $return->where('users.occupation', 'LIKE', '%' . $request->occupation . '%');
        }
        if (!empty($request->address)) {
            $return = $return->where('users.address', 'LIKE', '%' . $request->address . '%');
        }
        if (!empty($request->mobile_number)) {
            $return = $return->where('users.mobile_number', 'LIKE', '%' . $request->mobile_number . '%');
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
        $data['header_title'] = 'Parent list';
        return view('admin.parent.list', $data);
    }

    public function add()
    {
        $data['header_title'] = 'Add New Parent';
        return view('admin.parent.add', $data);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'gender' => 'required',
            'mobile_number' => 'required|min:10|max:10|regex:/^[0-9]+$/',
            'occupation' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->passes()) {

            $student = new User;
            $student->name = $request->name;
            $student->last_name = $request->last_name;
            $student->gender = $request->gender;
            $student->mobile_number = $request->mobile_number;
            $student->occupation = $request->occupation;

            if (!empty($request->file('profile_pic'))) {

                $ext = $request->file('profile_pic')->getClientOriginalExtension();
                $file = $request->file('profile_pic');
                $randomStr = Str::random(20);
                $filename = strtolower($randomStr) . '.' . $ext;
                $file->move(public_path() . '/uploads/profile/', $filename);

                $student->profile_pic = $filename;
            }

            $student->address = $request->address;
            $student->status = $request->status;
            $student->email = $request->email;
            $student->password = Hash::make($request->password);
            $student->user_type = 4;
            $student->save();

            return redirect('admin/parent/list')->with('success', 'Parent added successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function edit($id)
    {
        $data['getRecord'] = User::find($id);
        if (!empty($data['getRecord'])) {

            $data['header_title'] = 'Edit Parent';
            return view('admin.parent.edit', $data);
        } else {
            return redirect()->back()->with('error', 'Parent not found');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'gender' => 'required',
            'mobile_number' => 'required|min:10|max:10|regex:/^[0-9]+$/',
            'occupation' => 'required',
            'address' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->passes()) {

            $parent = User::find($id);
            $parent->name = $request->name;
            $parent->last_name = $request->last_name;
            $parent->gender = $request->gender;
            $parent->mobile_number = $request->mobile_number;
            $parent->occupation = $request->occupation;

            if (!empty($request->file('profile_pic'))) {

                if (!empty($parent->getProfile())) {
                    unlink('uploads/profile/' . $parent->profile_pic);
                }
                $ext = $request->file('profile_pic')->getClientOriginalExtension();
                $file = $request->file('profile_pic');
                $randomStr = Str::random(20);
                $filename = strtolower($randomStr) . '.' . $ext;
                $file->move(public_path() . '/uploads/profile/', $filename);
                $parent->profile_pic = $filename;
            }

            $parent->address = $request->address;
            $parent->status = $request->status;
            $parent->email = $request->email;

            if (!empty($request->password)) {
                $parent->password = Hash::make($request->password);
            }

            $parent->save();

            return redirect('admin/parent/list')->with('success', 'Parent updated successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function delete($id)
    {
        $parent = User::find($id);
        $parent->is_delete = 1;
        $parent->save();

        return redirect('admin/parent/list')->with('success', 'Parent deleted successfully');
    }

    public function myStudent(Request $request, $id)
    {

        $return = User::select('users.*', 'class.name as class_name', 'parent.name as parent_name', 'parent.last_name as parent_last_name')
            ->join('users as parent', 'parent.id', '=', 'users.parent_id', 'left')
            ->join('class', 'class.id', '=', 'users.class_id', 'left')
            ->where('users.user_type', '=', 3)
            ->where('users.is_delete', '=', 0);
        if (!empty($request->id)) {
            $return = $return->where('users.id', '=', $request->id);
        }
        if (!empty($request->name)) {
            $return = $return->where('users.name', 'LIKE', '%' . $request->name . '%');
        }
        if (!empty($request->last_name)) {
            $return = $return->where('users.last_name', 'LIKE', '%' . $request->last_name . '%');
        }
        if (!empty($request->email)) {
            $return = $return->where('users.email', 'LIKE', '%' . $request->email . '%');
        }
        $return = $return->orderBy('users.id', 'desc')
            ->limit(50)
            ->get();
        $return1 = User::select('users.*', 'class.name as class_name', 'parent.name as parent_name', 'parent.last_name as parent_last_name')
            ->join('users as parent', 'parent.id', '=', 'users.parent_id', 'left')
            ->join('class', 'class.id', '=', 'users.class_id', 'left')
            ->where('users.user_type', '=', 3)
            ->where('users.parent_id', '=', $id)
            ->where('users.is_delete', '=', 0)
            ->orderBy('users.id', 'desc')
            ->get();
        $data['getSearchStudent'] = $return;
        $data['getRecord'] = $return1;
        $data['getParent'] = User::find($id);;
        $data['parent_id'] = $id;
        $data['header_title'] = 'Parent Student list';
        return view('admin.parent.my_student', $data);
    }

    public function assignStudentParent($student_id, $parent_id)
    {
        $student = User::find($student_id);
        $student->parent_id = $parent_id;
        $student->save();

        return redirect()->back()->with('success', 'Student Assign successfully');
    }

    public function assignStudentParentDelete($student_id)
    {
        $student = User::find($student_id);
        $student->parent_id = null;
        $student->save();

        return redirect()->back()->with('success', 'Student Remove successfully');
    }

    public function MyStudentParent()
    {
        $id = Auth::user()->id;
        $return = User::select('users.*', 'class.name as class_name', 'parent.name as parent_name', 'parent.last_name as parent_last_name')
            ->join('users as parent', 'parent.id', '=', 'users.parent_id')
            ->join('class', 'class.id', '=', 'users.class_id', 'left')
            ->where('users.user_type', '=', 3)
            ->where('users.parent_id', '=', $id)
            ->where('users.is_delete', '=', 0)
            ->orderBy('users.id', 'desc')
            ->get();
        $data['getRecord'] = $return;
        $data['header_title'] = 'My Son/Daughter';
        return view('parent.mystudent', $data);
    }
}
