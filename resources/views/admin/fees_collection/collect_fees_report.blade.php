@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Collect Fees Report</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Search Collect Fees Report</h3>
                            </div>
                            <form action="" method="get">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Student ID</label>
                                            <input type="text" name="student_id" placeholder="Enter student ID"
                                                value="{{ Request::get('student_id') }}" class="form-control">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Student First Name</label>
                                            <input type="text" class="form-control" name="student_first_name"
                                                placeholder="Enter student first name"
                                                value="{{ Request::get('student_first_name') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Student Last Name</label>
                                            <input type="text" class="form-control" name="student_last_name"
                                                placeholder="Enter student last name"
                                                value="{{ Request::get('student_last_name') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Class</label>
                                            <select name="class_id" class="form-control">
                                                <option value="">Select Class</option>
                                                @foreach ($getClass as $class)
                                                    <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                        value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Created Date</label>
                                            <input type="date" class="form-control" name="created_date"
                                                value="{{ Request::get('created_date') }}">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <button type="submit" class="btn btn-primary"
                                                style="margin-top: 30px;">Search</button>
                                            <a href="{{ url('admin/fees_collection/collect_fees_report') }}"
                                                class="btn btn-success" style="margin-top: 30px;">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Collect Fees Report</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Class Name</th>
                                            <th>Total Fee Amount</th>
                                            <th>Paid Fee Amount</th>
                                            <th>Remaining Fee Amount</th>
                                            <th>Payment Type</th>
                                            <th>Remark</th>
                                            <th>Created By</th>
                                            <th>Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value->student_id }}</td>
                                                <td>{{ $value->student_name }} {{ $value->student_last_name }}</td>
                                                <td>{{ $value->class_name }}</td>
                                                <td>₹{{ number_format($value->total_fee_amount, 2) }}</td>
                                                <td>₹{{ number_format($value->paid_fee_amount, 2) }}</td>
                                                <td>₹{{ number_format($value->remaining_fee_amount, 2) }}</td>
                                                <td>{{ $value->payment_type }}</td>
                                                <td>{{ $value->remark }}</td>
                                                <td>{{ $value->created_by_name }}</td>
                                                <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" style="text-align: center">You are not paid your fee</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div style="padding: 10px; float:right;">
                                    {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
