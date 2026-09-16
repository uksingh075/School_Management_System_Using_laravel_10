<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function list(Request $request)
    {
        $return = User::select('users.*')
            ->where('user_type', '=', 1)
            ->where('is_delete', '=', 0);

        if (!empty($request->name)) {
            $return = $return->where('name', 'LIKE', '%' . $request->name . '%');
        }
        if (!empty($request->email)) {
            $return = $return->where('email', 'LIKE', '%' . $request->email . '%');
        }
        if (!empty($request->date)) {
            $return = $return->whereDate('created_at', '=', $request->date);
        }
        $return = $return->orderBy('id', 'desc')
            ->paginate(2);
        $data['getRecord'] = $return;
        $data['header_title'] = 'Admin list';
        return view('admin.admin.list', $data);
    }

    public function add()
    {
        $data['header_title'] = 'Add New Admin';
        return view('admin.admin.add', $data);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->passes()) {

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            if (!empty($request->file('profile_pic'))) {

                $ext = $request->file('profile_pic')->getClientOriginalExtension();
                $file = $request->file('profile_pic');
                $randomStr = Str::random(20);
                $filename = strtolower($randomStr) . '.' . $ext;
                $file->move(public_path() . '/uploads/profile/', $filename);

                $user->profile_pic = $filename;
            }

            $user->user_type = 1;
            $user->save();

            return redirect('admin/admin/list')->with('success', 'Admin added successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function edit($id)
    {
        $data['getRecord'] = User::find($id);
        if (!empty($data['getRecord'])) {
            $data['header_title'] = 'Edit Admin';
            return view('admin.admin.edit', $data);
        } else {
            return redirect()->back()->with('error', 'Admin not found');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:30|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email',
        ]);

        if ($validator->passes()) {

            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            if (!empty($request->file('profile_pic'))) {

                if (!empty($user->getProfile())) {
                    unlink('uploads/profile/' . $user->profile_pic);
                }
                $ext = $request->file('profile_pic')->getClientOriginalExtension();
                $file = $request->file('profile_pic');
                $randomStr = Str::random(20);
                $filename = strtolower($randomStr) . '.' . $ext;
                $file->move(public_path() . '/uploads/profile/', $filename);
                $user->profile_pic = $filename;
            }

            $user->save();

            return redirect('admin/admin/list')->with('success', 'Admin updated successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->is_delete = 1;
        $user->save();

        return redirect('admin/admin/list')->with('success', 'Admin deleted successfully');
    }
}
