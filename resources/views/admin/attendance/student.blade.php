@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Student Attendance</h1>
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
                                <h3 class="card-title">Search Student Attendance</h3>
                            </div>
                            <form action="" method="get">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="form-group col-md-3">
                                            <label>Class</label>
                                            <select name="class_id" class="form-control" id="GetClass" required>
                                                <option value="">Select Class</option>
                                                @foreach ($getClass as $class)
                                                    <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                        value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Attendance Date</label>
                                            <input type="date" name="attendance_date"
                                                value="{{ Request::get('attendance_date') }}" class="form-control">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-primary"
                                                style="margin-top: 30px;">Search</button>
                                            <a href="{{ url('admin/attendance/student') }}" class="btn btn-success"
                                                style="margin-top: 30px;">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @if (!empty(Request::get('class_id')) && Request::get('attendance_date'))
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
                                                <th>Attendance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($getStudent) && !empty($getStudent->count()))
                                                @foreach ($getStudent as $student)
                                                    @php
                                                        $attendancetype = '';
                                                        $attendance = App\Models\StudentAttendance::where('student_id','=', $student->id)
                                                            ->where('class_id', '=', Request::get('class_id'))
                                                            ->where('attendance_date', '=', Request::get('attendance_date'))->first();

                                                        if(!empty($attendance->attendance_type))
                                                        {
                                                            $attendancetype = $attendance->attendance_type;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $student->id }}</td>
                                                        <td>{{ $student->name }} {{ $student->last_name }}</td>
                                                        <td>
                                                            <label style="margin-right: 10px;">
                                                                <input type="radio" name="attendance{{ $student->id }}"
                                                                    value="1" id="{{ $student->id }}" {{ $attendancetype == 1 ? 'checked' : '' }}
                                                                    class="SaveAttendance">Present
                                                            </label>
                                                            <label style="margin-right: 10px;">
                                                                <input type="radio" name="attendance{{ $student->id }}"
                                                                    value="2" id="{{ $student->id }}" {{ $attendancetype == 2 ? 'checked' : '' }}
                                                                    class="SaveAttendance">Absent
                                                            </label>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('.SaveAttendance').click(function(e) {

            var class_id = $('#GetClass').val();
            var attendance_date = $('#GetAttendanceDate').val();
            var student_id = $(this).attr('id');
            var attendance_type = $(this).val();

            $.ajax({
                url: "{{ url('admin/attendance/student/save') }}",
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    class_id: class_id,
                    attendance_date: attendance_date,
                    student_id: student_id,
                    attendance_type: attendance_type,
                },
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    location.reload();
                }
            });
        });
    </script>
@endsection
