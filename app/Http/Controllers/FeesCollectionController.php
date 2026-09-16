<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Setting;
use App\Models\StudentAddFees;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeesCollectionController extends Controller
{
    public function CollectFees(Request $request)
    {
        $data['getClass'] = ClassModel::select('class.*')
            ->join('users', 'users.id', 'class.created_by')
            ->where('class.is_delete', '=', 0)
            ->where('class.is_delete', '=', 0)
            ->orderBy('class.name', 'asc')->get();

        $return = User::select('users.*', 'class.name as class_name', 'class.fee_amount')
            ->join('class', 'class.id', '=', 'users.class_id')
            ->where('users.user_type', '=', 3)
            ->where('users.is_delete', '=', 0);

        if (!empty($request->class_id)) {
            $return = $return->where('users.class_id', '=',  $request->class_id);
        }

        if (!empty($request->student_id)) {
            $return = $return->where('users.id', '=',  $request->student_id);
        }

        if (!empty($request->first_name)) {
            $return = $return->where('users.name', 'LIKE', '%' . $request->first_name . '%');
        }

        if (!empty($request->last_name)) {
            $return = $return->where('users.last_name', 'LIKE', '%' . $request->last_name . '%');
        }

        $return = $return->orderBy('users.name', 'asc')
            ->paginate(10);

        if (!empty($request->all())) {
            $data['getRecord'] = $return;
        }

        $data['header_title'] = 'Collect Fees';
        return view('admin.fees_collection.collect_fees', $data);
    }

    public function AddCollectFees($student_id)
    {
        $getStudent = User::select('users.*', 'class.fee_amount', 'class.name as class_name')
            ->join('class', 'class.id', 'users.class_id')
            ->where('users.id', '=', $student_id)->first();

        $data['getFees'] = StudentAddFees::select('student_add_fees.*', 'class.name as class_name', 'users.name as created_by_name')
            ->join('class', 'class.id', '=', 'student_add_fees.class_id')
            ->join('users', 'users.id', '=', 'student_add_fees.created_by')
            ->where('student_add_fees.is_payment', '=', 1)
            ->where('student_add_fees.student_id', '=', $student_id)->get();

        $data['paid_fee_amount'] = StudentAddFees::where('student_add_fees.class_id', '=', $getStudent->class_id)
            ->where('student_add_fees.student_id', '=', $student_id)
            ->where('student_add_fees.is_payment', '=', 1)
            ->sum('student_add_fees.paid_fee_amount');

        $data['getStudent'] = $getStudent;

        $data['header_title'] = 'Add Collect Fees';
        return view('admin.fees_collection.add_collect_fees', $data);
    }

    public function InsertCollectFees($student_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'payment_type' => 'required',
        ]);

        if ($validator->passes()) {

            $getStudent = User::select('users.*', 'class.fee_amount', 'class.name as class_name')
                ->join('class', 'class.id', 'users.class_id')
                ->where('users.id', '=', $student_id)->first();

            $paid_fee_amount = StudentAddFees::where('student_add_fees.class_id', '=', $getStudent->class_id)
                ->where('student_add_fees.student_id', '=', $student_id)
                ->where('student_add_fees.is_payment', '=', 1)
                ->sum('student_add_fees.paid_fee_amount');

            $total_fee_amount = $getStudent->fee_amount - $paid_fee_amount;

            if ($total_fee_amount >= $request->amount) {

                $remaining_fee_amount = $total_fee_amount - $request->amount;

                $payment = new StudentAddFees;
                $payment->student_id = $student_id;
                $payment->class_id = $getStudent->class_id;
                $payment->total_fee_amount = $total_fee_amount;
                $payment->paid_fee_amount = $request->amount;
                $payment->remaining_fee_amount = $remaining_fee_amount;
                $payment->payment_type = $request->payment_type;
                $payment->remark = $request->remark;
                $payment->is_payment = 1;
                $payment->created_by = Auth::user()->id;
                $payment->save();

                return redirect()->back()->with('success', 'Fees Paid Successfully');
            } else {
                return redirect()->back()->with('error', 'Your amount is greater than remaining amount');
            }
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function CollectFeesReport(Request $request)
    {
        $data['getClass'] = ClassModel::select('class.*')
            ->join('users', 'users.id', 'class.created_by')
            ->where('class.is_delete', '=', 0)
            ->where('class.is_delete', '=', 0)
            ->orderBy('class.name', 'asc')->get();

        $return = StudentAddFees::select('student_add_fees.*', 'class.name as class_name', 'users.name as created_by_name','student.name as student_name', 'student.last_name as student_last_name')
            ->join('class', 'class.id', '=', 'student_add_fees.class_id')
            ->join('users as student', 'student.id', '=', 'student_add_fees.student_id')
            ->join('users', 'users.id', '=', 'student_add_fees.created_by')
            ->where('student_add_fees.is_payment', '=', 1);

        if (!empty($request->student_id)) {
            $return = $return->where('student_add_fees.student_id', '=', $request->student_id);
        }

        if (!empty($request->student_first_name)) {
            $return = $return->where('student.name', 'like', '%' . $request->student_first_name . '%');
        }

        if (!empty($request->student_last_name)) {
            $return = $return->where('student.last_name', 'like', '%' . $request->student_last_name . '%');
        }

        if (!empty($request->class_id)) {
            $return = $return->where('student_add_fees.class_id', '=', $request->class_id);
        }

        if (!empty($request->created_date)) {
            $return = $return->whereDate('student_add_fees.created_at', '=', $request->created_date);
        }

        $return = $return->orderBy('student_add_fees.id', 'desc')->paginate(20);

        $data['getRecord'] = $return;
        $data['header_title'] = 'Collect Fees Report';
        return view('admin.fees_collection.collect_fees_report', $data);
    }

    //Student Side
    public function StudentCollectFees()
    {
        $student_id = Auth::user()->id;

        $getStudent = User::find($student_id);

        // $getStudent = User::select('users.*', 'class.fee_amount', 'class.name as class_name')
        //     ->join('class', 'class.id', 'users.class_id')
        //     ->where('users.id', '=', $student_id)->first();

        $data['getFees'] = StudentAddFees::select('student_add_fees.*', 'class.name as class_name', 'users.name as created_by_name')
            ->join('class', 'class.id', '=', 'student_add_fees.class_id')
            ->join('users', 'users.id', '=', 'student_add_fees.created_by')
            ->where('student_add_fees.is_payment', '=', 1)
            ->where('student_add_fees.student_id', '=', $student_id)->get();

        $data['paid_fee_amount'] = StudentAddFees::where('student_add_fees.class_id', '=', $getStudent->class_id)
            ->where('student_add_fees.student_id', '=', $student_id)
            ->where('student_add_fees.is_payment', '=', 1)
            ->sum('student_add_fees.paid_fee_amount');

        $data['header_title'] = 'Fees Collection';
        return view('student.fees_collection', $data);
    }

    //Parent Side
    public function ParentCollectFees($student_id)
    {
        $getStudent = User::find($student_id);

        // $getStudent = User::select('users.*', 'class.fee_amount', 'class.name as class_name')
        //     ->join('class', 'class.id', 'users.class_id')
        //     ->where('users.id', '=', $student_id)->first();

        $data['getFees'] = StudentAddFees::select('student_add_fees.*', 'class.name as class_name', 'users.name as created_by_name')
            ->join('class', 'class.id', '=', 'student_add_fees.class_id')
            ->join('users', 'users.id', '=', 'student_add_fees.created_by')
            ->where('student_add_fees.is_payment', '=', 1)
            ->where('student_add_fees.student_id', '=', $student_id)->get();

        $data['paid_fee_amount'] = StudentAddFees::where('student_add_fees.class_id', '=', $getStudent->class_id)
            ->where('student_add_fees.student_id', '=', $student_id)
            ->where('student_add_fees.is_payment', '=', 1)
            ->sum('student_add_fees.paid_fee_amount');

        $data['getStudent'] = $getStudent;

        $data['header_title'] = 'Fees Collection';
        return view('parent.fees_collection', $data);
    }
}
