@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Attendance Report <span style="color: blue;">(Total : {{ $getRecord->total() }})</span></h1>
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
                                <h3 class="card-title">Search Attendance Report</h3>
                            </div>
                            <form action="" method="get">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-2">
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

                                        <div class="form-group col-md-2">
                                            <label>Class</label>
                                            <select name="class_id" class="form-control">
                                                <option value="">Select Class</option>
                                                @foreach ($getClass as $class)
                                                    <option
                                                        {{ Request::get('class_id') == $class->class_id ? 'selected' : '' }}
                                                        value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>From Attendance Date</label>
                                            <input type="date" name="from_attendance_date"
                                                value="{{ Request::get('from_attendance_date') }}" class="form-control">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>To Attendance Date</label>
                                            <input type="date" name="to_attendance_date"
                                                value="{{ Request::get('to_attendance_date') }}" class="form-control">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Attendance Type</label>
                                            <select name="attendance_type" class="form-control">
                                                <option value="">Select Attendance Type</option>
                                                <option {{ Request::get('attendance_type') == 1 ? 'selected' : '' }}
                                                    value="1">Present</option>
                                                <option {{ Request::get('attendance_type') == 2 ? 'selected' : '' }}
                                                    value="2">Absent</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-primary"
                                                style="margin-top: 30px;">Search</button>
                                            <a href="{{ url('teacher/attendance/report') }}" class="btn btn-success"
                                                style="margin-top: 30px;">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Student List</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped" style="overflow: auto;">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Class Name</th>
                                            <th>Attendance Type</th>
                                            <th>Attendance Date</th>
                                            <th>Created By</th>
                                            <th>Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td>{{ $value->student_id }}</td>
                                                <td>{{ $value->student_name }} {{ $value->student_last_name }}</td>
                                                <td>{{ $value->class_name }}</td>
                                                <td>
                                                    @if ($value->attendance_type == 1)
                                                        Present
                                                    @elseif($value->attendance_type == 2)
                                                        Absent
                                                    @endif
                                                </td>
                                                <td>{{ date('d-m-Y', strtotime($value->attendance_date)) }}</td>
                                                <td>{{ $value->createdby_name }}</td>
                                                <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%" style="text-align: center;">Records Not Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    @if (!empty($getRecord))
                                        <div style="padding: 10px; float:right;">
                                            {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endsection
