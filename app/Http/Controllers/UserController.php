<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function Setting()
    {
        $data['getRecord'] = Setting::find(1);
        $data['header_title'] = 'Setting';
        return view('admin.setting', $data);
    }

    public function UpdateSetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paypal_email' => 'required|email',
        ]);

        if ($validator->passes()) {

            $setting = Setting::find(1);
            $setting->paypal_email = $request->paypal_email;
            $setting->save();

            return redirect()->back()->with('success', 'Setting updated successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
    public function MyAccount()
    {
        $data['getRecord'] = User::find(Auth::user()->id);
        $data['header_title'] = 'My Account';
        if (Auth::user()->user_type == 1) {
            return view('admin.myaccount', $data);
        } else if (Auth::user()->user_type == 2) {
            return view('teacher.myaccount', $data);
        } else if (Auth::user()->user_type == 3) {
            return view('student.myaccount', $data);
        } else if (Auth::user()->user_type == 4) {
            return view('parent.myaccount', $data);
        }
    }

    public function UpdateAdminMyAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email',
        ]);

        if ($validator->passes()) {

            $user = User::find(Auth::user()->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return redirect()->back()->with('success', 'Account updated successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
    public function UpdateTeacherMyAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'mobile_number' => 'required|min:10|max:10|regex:/^[0-9]+$/',
            'marital_status' => 'required',
            'address' => 'required',
            'permanent_address' => 'required',
            'qualification' => 'required',
            'work_experience' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->passes()) {

            $teacher = User::find(Auth::user()->id);
            $teacher->name = $request->name;
            $teacher->last_name = $request->last_name;
            $teacher->gender = $request->gender;

            if (!empty($request->date_of_birth)) {
                $teacher->date_of_birth = $request->date_of_birth;
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
            $teacher->email = $request->email;

            $teacher->save();

            return redirect()->back()->with('success', 'Account updated successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function UpdateStudentMyAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'caste' => 'required',
            'religion' => 'required',
            'mobile_number' => 'required|min:10|max:10|regex:/^[0-9]+$/',
            'height' => 'required',
            'weight' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->passes()) {

            $student = User::find(Auth::user()->id);
            $student->name = $request->name;
            $student->last_name = $request->last_name;
            $student->gender = $request->gender;

            if (!empty($request->date_of_birth)) {
                $student->date_of_birth = $request->date_of_birth;
            }

            $student->caste = $request->caste;
            $student->religion = $request->religion;
            $student->mobile_number = $request->mobile_number;

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
            $student->email = $request->email;

            $student->save();

            return redirect()->back()->with('success', 'Account updated successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function UpdateParentMyAccount(Request $request)
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

            $parent = User::find(Auth::user()->id);
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
            $parent->email = $request->email;

            $parent->save();

            return redirect()->back()->with('success', 'Account updated successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }


    public function change_password()
    {
        $data['header_title'] = 'Change Password';
        return view('profile.change_password', $data);
    }

    public function update_change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
        ]);

        if ($validator->passes()) {

            $user = User::find(Auth::user()->id);
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                return redirect()->back()->with('success', 'Password updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Old password does not match.');
            }
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
}
