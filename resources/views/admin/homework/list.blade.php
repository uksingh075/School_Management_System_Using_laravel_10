@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Homework (Total : {{ $getRecord->total() }})</h1>

                    </div>
                    <div class="col-sm-6" style="text-align: right">
                        <a href="{{ URL::to('/') }}/admin/homework/homework/add" class="btn btn-primary">Add New
                            Homework</a>
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
                                <h3 class="card-title">Search Homework</h3>
                            </div>
                            <form action="" method="get">
                                <div class="card-body">
                                    <div class="row">
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
                                            <button type="submit" class="btn btn-primary"
                                                style="margin-top: 30px;">Search</button>
                                            <a href="{{ url('admin/homework/homework') }}" class="btn btn-success"
                                                style="margin-top: 30px;">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @include('message')
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Homework List</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="min-width: 100px;">Class</th>
                                            <th style="min-width: 100px;">Subject</th>
                                            <th style="min-width: 150px;">Homework Date</th>
                                            <th style="min-width: 150px;">Submission Date</th>
                                            <th style="min-width: 100px;">Created By</th>
                                            <th style="min-width: 100px;">Created Date</th>
                                            <th style="min-width: 100px;">Document</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value->class_name }}</td>
                                                <td>{{ $value->subject_name }}</td>
                                                <td>{{ date('d-m-Y', strtotime($value->homework_date)) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($value->submission_date)) }}</td>
                                                <td>{{ $value->created_by_name }}</td>
                                                <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                <td>
                                                    @if (!empty($value->getDocument()))
                                                        <a href="{{ $value->getDocument() }}" class="btn btn-warning"
                                                            download="">Download Homework</a>
                                                    @endif
                                                </td>
                                                <td style="min-width: 200px;">
                                                    <a href="{{ url('admin/homework/homework/edit/' . $value->id) }}"
                                                        class="btn btn-primary">Edit</a>
                                                    <a href="{{ url('admin/homework/homework/delete/' . $value->id) }}"
                                                        class="btn btn-danger">Delete</a>
                                                        
                                                    <a href="{{ url('admin/homework/homework/submitted/' . $value->id) }}"
                                                        class="btn btn-warning">View Submitted Homework</a>
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
