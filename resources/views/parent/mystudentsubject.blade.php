@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Student Subject <span style="color:blue;">({{ $getUser->name }} {{ $getUser->last_name }})</span>
                        </h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @include('message')
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Student Subject</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Subject Name</th>
                                            <th>Subject Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($getRecord->isNotEmpty())
                                            @foreach ($getRecord as $value)
                                                <tr>
                                                    <td>{{ $value->subject_name }}</td>
                                                    <td>{{ $value->subject_type }}</td>
                                                    <td>
                                                        <a href="{{ url('parent/my_student/subject/class_timetable/' . $value->class_id . '/' . $value->subject_id) }}"
                                                            class="btn btn-primary">Class Timetable</a>
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
