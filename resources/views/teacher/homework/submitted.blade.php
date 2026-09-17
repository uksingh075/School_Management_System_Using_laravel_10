@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Submitted Homework</h1>
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
                                <h3 class="card-title">Search Submitted Homework</h3>
                            </div>
                            <form action="" method="get">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label>Student First Name</label>
                                            <input type="text" class="form-control" name="student_first_name"
                                                placeholder="Enter student first name" value="{{ Request::get('student_first_name') }}">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Student Last Name</label>
                                            <input type="text" class="form-control" name="student_last_name"
                                                placeholder="Enter student last name" value="{{ Request::get('student_last_name') }}">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>From Submitted Date</label>
                                            <input type="date" class="form-control" name="from_submitted_date"
                                                value="{{ Request::get('from_submitted_date') }}">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>To Submitted Date</label>
                                            <input type="date" class="form-control" name="to_submitted_date"
                                                value="{{ Request::get('to_submitted_date') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-primary"
                                                style="margin-top: 30px;">Search</button>
                                            <a href="{{ url('teacher/homework/homework/submitted/' . $homework_id) }}"
                                                class="btn btn-success" style="margin-top: 30px;">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @include('message')
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Submitted Homework List</h3>
                            </div>
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student Name</th>
                                            <th>Description</th>
                                            <th>Submitted Date</th>
                                            <th>Document</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value->first_name }} {{ $value->last_name }}</td>
                                                </td>
                                                <td>{!! $value->description !!}</td>
                                                <td>
                                                    {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}
                                                </td>
                                                <td style="min-width: 200px;">
                                                    @if (!empty($value->getDocument()))
                                                        <a href="{{ $value->getDocument() }}"
                                                            class="btn btn-warning" download="">Download Submitted Homework</a>
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
