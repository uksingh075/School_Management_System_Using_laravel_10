@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Homework Report</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12" style="">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Search Homework Report</h3>
                            </div>
                            <form action="" method="get">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Student First Name</label>
                                            <input type="text" class="form-control" name="student_first_name"
                                                placeholder="Enter student first name" value="{{ Request::get('student_first_name') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Student Last Name</label>
                                            <input type="text" class="form-control" name="student_last_name"
                                                placeholder="Enter student last name" value="{{ Request::get('student_last_name') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Class</label>
                                            <input type="text" class="form-control" name="class"
                                                placeholder="Enter class" value="{{ Request::get('class') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Subject</label>
                                            <input type="text" class="form-control" name="subject"
                                                placeholder="Enter subject" value="{{ Request::get('subject') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>From Homework Date</label>
                                            <input type="date" class="form-control" name="from_homework_date"
                                                value="{{ Request::get('from_homework_date') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>To Homework Date</label>
                                            <input type="date" class="form-control" name="to_homework_date"
                                                value="{{ Request::get('to_homework_date') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>From Submission Date</label>
                                            <input type="date" class="form-control" name="from_submission_date"
                                                value="{{ Request::get('from_submission_date') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>To Submission Date</label>
                                            <input type="date" class="form-control" name="to_submission_date"
                                                value="{{ Request::get('to_submission_date') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>From Submitted Date</label>
                                            <input type="date" class="form-control" name="from_submitted_date"
                                                value="{{ Request::get('from_submitted_date') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>To Submitted Date</label>
                                            <input type="date" class="form-control" name="to_submitted_date"
                                                value="{{ Request::get('to_submitted_date') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-primary"
                                                style="margin-top: 30px;">Search</button>
                                            <a href="{{ url('admin/homework/report') }}" class="btn btn-success"
                                                style="margin-top: 30px;">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @include('message')
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Homework Report List</h3>
                            </div>
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="min-width: 150px;">Student Name</th>
                                            <th>Class</th>
                                            <th>Subject</th>
                                            <th style="min-width: 150px;">Homework Date</th>
                                            <th style="min-width: 150px;">Submission Date</th>
                                            <th>Description</th>
                                            <th style="min-width: 150px;">Created Date</th>
                                            <th>Document</th>
                                            <th style="min-width: 200px;">Submitted Description</th>
                                            <th style="min-width: 150px;">Submitted Date</th>
                                            <th>Submitted Document</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value->first_name }} {{ $value->last_name }}</td>
                                                <td>{{ $value->class_name }}</td>
                                                <td>{{ $value->subject_name }}</td>
                                                <td>{{ date('d-m-Y', strtotime($value->getHomework->homework_date)) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($value->getHomework->submission_date)) }}
                                                </td>
                                                <td>{!! $value->getHomework->description !!}</td>
                                                <td style="min-width: 170px;">
                                                    {{ date('d-m-Y H:i A', strtotime($value->getHomework->created_at)) }}
                                                </td>
                                                <td style="min-width: 200px;">
                                                    @if (!empty($value->getHomework->getDocument()))
                                                        <a href="{{ $value->getHomework->getDocument() }}"
                                                            class="btn btn-warning" download="">Download Homework</a>
                                                    @endif
                                                </td>
                                                <td>{!! $value->description !!}</td>
                                                <td style="min-width: 170px;">
                                                    {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}
                                                </td>
                                                <td style="min-width: 300px;">
                                                    @if (!empty($value->getDocument()))
                                                        <a href="{{ $value->getDocument() }}" class="btn btn-warning"
                                                            download="">Download Submitted Homework</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" style="text-align: center">Records Not Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div style="padding: 10px; float:right;">
                                    {{-- {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!} --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
