@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-9">
                        <h1>My Son/Daughter</h1>
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
                                <h3 class="card-title">My Son/Daughter</h3>
                            </div>
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Profile Pic</th>
                                            <th>Student Name</th>
                                            <th>Email</th>
                                            <th>Admission Number</th>
                                            <th>Roll Number</th>
                                            <th>Class</th>
                                            <th>Gender</th>
                                            <th>Date Of Birth</th>
                                            <th>Mobile Number</th>
                                            <th>Admission Date</th>
                                            <th>Blood Group</th>
                                            <th>Height</th>
                                            <th>Weight</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($getRecord->isNotEmpty())
                                            @foreach ($getRecord as $value)
                                                <tr>
                                                    <td>
                                                        @if (!empty($value->getProfile()))
                                                            <img src="{{ $value->getProfile() }}"
                                                                style="width: 50px; height: 50px; border-radius: 50px;"
                                                                alt="img">
                                                        @endif
                                                    </td>
                                                    <td>{{ $value->name }} {{ $value->last_name }}</td>
                                                    <td>{{ $value->email }}</td>
                                                    <td>{{ $value->admission_number }}</td>
                                                    <td>{{ $value->roll_number }}</td>
                                                    <td>{{ $value->class_name }}</td>
                                                    <td>{{ $value->gender }}</td>
                                                    <td>
                                                        @if (!empty($value->date_of_birth))
                                                            {{ date('d-m-Y', strtotime($value->date_of_birth)) }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $value->mobile_number }}</td>
                                                    <td>
                                                        @if (!empty($value->admission_date))
                                                            {{ date('d-m-Y', strtotime($value->admission_date)) }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $value->blood_group }}</td>
                                                    <td>{{ $value->height }}</td>
                                                    <td>{{ $value->weight }}</td>
                                                    <td>
                                                        {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}
                                                    </td>
                                                    <td style="min-width: 550px;">
                                                        <a href="{{ url('parent/my_student/subject/' . $value->id) }}"
                                                            style="margin-bottom: 10px;" class="btn btn-success">Subject</a>
                                                        <a href="{{ url('parent/my_student/exam_timetable/' . $value->id) }}"
                                                            style="margin-bottom: 10px;" class="btn btn-primary">Exam TimeTable</a>
                                                        <a href="{{ url('parent/my_student/exam_result/' . $value->id) }}"
                                                            style="margin-bottom: 10px;" class="btn btn-primary">Exam Result</a>
                                                        <a href="{{ url('parent/my_student/attendance/' . $value->id) }}"
                                                            style="margin-bottom: 10px;" class="btn btn-primary">Attendance</a>
                                                        <a href="{{ url('parent/my_student/homework/' . $value->id) }}"
                                                            style="margin-bottom: 10px;" class="btn btn-primary">Homework</a>
                                                        <a href="{{ url('parent/my_student/submitted_homework/' . $value->id) }}"
                                                            style="margin-bottom: 10px;" class="btn btn-success">Submitted Homework</a>
                                                        <a href="{{ url('parent/my_student/fees_collection/' . $value->id) }}"
                                                            style="margin-bottom: 10px;" class="btn btn-warning">Fees Collection</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="100%" style="text-align: center">Records Not Found</td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
