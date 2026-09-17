@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Class & Subject</h1>
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
                                <h3 class="card-title">My Class & Subject</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Class Name</th>
                                            <th>Subject Name</th>
                                            <th>Subject Type</th>
                                            <th>My Class Timetable</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($getRecord->isNotEmpty())
                                            @foreach ($getRecord as $value)
                                                <tr>
                                                    <td>{{ $value->class_name }}</td>
                                                    <td>{{ $value->subject_name }}</td>
                                                    <td>{{ $value->subject_type }}</td>
                                                    <td>
                                                        @php
                                                             $ClassSubject = $value->getMyTimetable($value->class_id,$value->subject_id)
                                                        @endphp
                                                        @if(!empty($ClassSubject))
                                                            {{ !empty($ClassSubject->start_time) ? date('h:i A', strtotime($ClassSubject->start_time)) : '' }} to
                                                            {{ !empty($ClassSubject->end_time) ? date('h:i A', strtotime($ClassSubject->end_time)) : '' }}
                                                            <br> Room Number : {{ $ClassSubject->room_number }}
                                                        @endif
                                                    </td>
                                                    <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                    <td>
                                                        <a href="{{ url('teacher/my_class_subject/class_timetable/' . $value->class_id . '/' . $value->subject_id) }}"
                                                            class="btn btn-primary">My Class Timetable</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" style="text-align: center">Records Not Found</td>
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
