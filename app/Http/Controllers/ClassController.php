<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    public function list(Request $request)
    {
        $return = ClassModel::select('class.*', 'users.name as created_by_name')
            ->join('users', 'users.id', 'class.created_by');

        if (!empty($request->name)) {
            $return = $return->where('class.name', 'LIKE', '%' . $request->name . '%');
        }
        if (!empty($request->date)) {
            $return = $return->whereDate('class.created_at', '=', $request->date);
        }

        $return =  $return->where('class.is_delete', '=', 0)
            ->orderBy('class.id', 'desc')
            ->paginate(2);
        $data['getRecord'] = $return;
        $data['header_title'] = 'Class list';
        return view('admin.class.list', $data);
    }

    public function add()
    {
        $data['header_title'] = 'Add New Class';
        return view('admin.class.add', $data);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|regex:/^[a-zA-Z\s]+$/',
            'fee_amount' => 'required|min:3',
        ]);

        if ($validator->passes()) {

            $class = new ClassModel;
            $class->name = $request->name;
            $class->fee_amount = $request->fee_amount;
            $class->status = $request->status;
            $class->created_by = Auth::user()->id;
            $class->save();

            return redirect('admin/class/list')->with('success', 'Class added successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function edit($id)
    {
        $data['getRecord'] = ClassModel::find($id);
        if (!empty($data['getRecord'])) {
            $data['header_title'] = 'Edit Class';
            return view('admin.class.edit', $data);
        } else {
            return redirect()->back()->with('error', 'Class not found');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|regex:/^[a-zA-Z\s]+$/',
            'fee_amount' => 'required|min:3',
        ]);

        if ($validator->passes()) {

            $class = ClassModel::find($id);
            $class->name = $request->name;
            $class->fee_amount = $request->fee_amount;
            $class->status = $request->status;
            $class->save();

            return redirect('admin/class/list')->with('success', 'Class updated successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function delete($id)
    {
        $class = ClassModel::find($id);
        $class->is_delete = 1;
        $class->save();
        return redirect('admin/class/list')->with('success', 'Class deleted successfully');
    }
}
