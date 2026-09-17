@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Collect Fees</h1>
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
                                <h3 class="card-title">Search Collect Fees Student</h3>
                            </div>
                            <form action="" method="get">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label>Class Name</label>
                                            <select name="class_id" class="form-control">
                                                <option value="">Select Class</option>
                                                @foreach ($getClass as $class)
                                                    <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                        value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Student ID</label>
                                            <input type="text" class="form-control" name="student_id"
                                                placeholder="Enter class Student ID"
                                                value="{{ Request::get('student_id') }}">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Student First Name</label>
                                            <input type="text" class="form-control" name="first_name"
                                                placeholder="Enter First Name" value="{{ Request::get('first_name') }}">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Student Last Name</label>
                                            <input type="text" class="form-control" name="last_name"
                                                placeholder="Enter Last Name" value="{{ Request::get('last_name') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-primary"
                                                style="margin-top: 30px;">Search</button>
                                            <a href="{{ url('admin/fees_collection/collect_fees') }}"
                                                class="btn btn-success" style="margin-top: 30px;">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @include('message')
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Student List</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Class Name</th>
                                            <th>Total Fee Amount</th>
                                            <th>Paid Fee Amount</th>
                                            <th>Remaining Fee Amount</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($getRecord))
                                            @forelse ($getRecord as $value)
                                                @php
                                                    $paid_fee_amount = $value->getPaidFeeAmount($value->id,$value->class_id);
                                                    $remaining_fee_amount = $value->fee_amount - $paid_fee_amount;
                                                @endphp
                                                <tr>
                                                    <td>{{ $value->id }}</td>
                                                    <td>{{ $value->name }} {{ $value->last_name }}</td>
                                                    <td>{{ $value->class_name }}</td>
                                                    <td>₹{{ number_format($value->fee_amount, 2) }}</td>
                                                    <td>₹{{ number_format($paid_fee_amount, 2) }}</td>
                                                    <td>₹{{ number_format($remaining_fee_amount, 2) }}</td>
                                                    <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                    <td style="min-width: 150px;">
                                                        <a href="{{ url('admin/fees_collection/collect_fees/add_fees/'.$value->id) }}" class="btn btn-success">Collect Fees</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%" style="text-align: center">Records Not Found</td>
                                                </tr>
                                            @endforelse
                                        @else
                                            <tr>
                                                <td colspan="100%" style="text-align: center">Records Not Found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div style="padding: 10px; float:right;">
                                    @if (!empty($getRecord))
                                        {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
