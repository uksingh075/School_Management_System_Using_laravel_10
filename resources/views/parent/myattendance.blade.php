@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Attendance <span style="color:blue;">({{ $getStudent->name }}
                                {{ $getStudent->last_name }})</span></h1>
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
                                <h3 class="card-title">Search Attendance</h3>
                            </div>
                            <form action="" method="get">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label>From Attendance Date</label>
                                            <input type="date" name="from_attendance_date"
                                                value="{{ Request::get('from_attendance_date') }}" class="form-control">
                                        </div>

                                        <div class="form-group col-md-2">
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
                                            <a href="{{ url('parent/my_student/attendance/' . $getStudent->id) }}"
                                                class="btn btn-success" style="margin-top: 30px;">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">My Attendance</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped" style="overflow: auto;">
                                    <thead>
                                        <tr>
                                            <th>Class Name</th>
                                            <th>Attendance Type</th>
                                            <th>Attendance Date</th>
                                            <th>Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td>{{ $value->class_name }}</td>
                                                <td>
                                                    @if ($value->attendance_type == 1)
                                                        Present
                                                    @elseif($value->attendance_type == 2)
                                                        Absent
                                                    @endif
                                                </td>
                                                <td>{{ date('d-m-Y', strtotime($value->attendance_date)) }}</td>
                                                <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%">Records Not Found</td>
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
