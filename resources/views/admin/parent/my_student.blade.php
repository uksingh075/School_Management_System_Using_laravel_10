@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-9">
                        <h1>Parent Student List ({{ $getParent->name }} {{ $getParent->last_name }})</h1>
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
                                <h3 class="card-title">Search Student</h3>
                            </div>
                            <form action="" method="get">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label>Student ID</label>
                                            <input type="text" class="form-control" name="id"
                                                placeholder="Enter student id" value="{{ Request::get('id') }}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Name</label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="Enter name" value="{{ Request::get('name') }}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control" name="last_name"
                                                placeholder="Enter last name" value="{{ Request::get('last_name') }}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Email address</label>
                                            <input type="text" class="form-control" name="email"
                                                placeholder="Enter email" value="{{ Request::get('email') }}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-primary"
                                                style="margin-top: 30px;">Search</button>
                                            <a href="{{ url('admin/parent/my-student/' . $parent_id) }}"
                                                class="btn btn-success" style="margin-top: 30px;">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @include('message')
                        @if ($getSearchStudent->isNotEmpty())
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Student List</h3>
                                </div>
                                <div class="card-body p-0" style="overflow: auto;">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Profile Pic</th>
                                                <th>Student Name</th>
                                                <th>Email</th>
                                                <th>Parent Name</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($getSearchStudent as $value)
                                                <tr>
                                                    <td>{{ $value->id }}</td>
                                                    <td>
                                                        @if (!empty($value->getProfile()))
                                                            <img src="{{ $value->getProfile() }}"
                                                                style="width: 50px; height: 50px; border-radius: 50px;"
                                                                alt="img">
                                                        @endif
                                                    </td>
                                                    <td>{{ $value->name }} {{ $value->last_name }}</td>
                                                    <td>{{ $value->email }}</td>
                                                    <td>{{ $value->parent_name }} {{ $value->parent_last_name }}</td>
                                                    <td>
                                                        {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                    <td>
                                                        @if($value->parent_id == null)
                                                            <a href="{{ url('admin/parent/assign_student_parent/' . $value->id . '/' . $parent_id) }}"
                                                            class="btn btn-primary btn-sm">Add Student to Parent</a>
                                                        @else
                                                            <p style="color: green;">Student Already Assigned</p>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            {{-- @else
                                            <tr>
                                                <td colspan="11" style="text-align: center">Records Not Found</td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                    <div style="padding: 10px; float: right;">
                                        {{-- {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!} --}}
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Parent Student List</h3>
                            </div>
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Profile Pic</th>
                                            <th>Student Name</th>
                                            <th>Email</th>
                                            <th>Parent Name</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($getRecord->isNotEmpty())
                                            @foreach ($getRecord as $value)
                                                <tr>
                                                    <td>{{ $value->id }}</td>
                                                    <td>
                                                        @if (!empty($value->getProfile()))
                                                            <img src="{{ $value->getProfile() }}"
                                                                style="width: 50px; height: 50px; border-radius: 50px;"
                                                                alt="img">
                                                        @endif
                                                    </td>
                                                    <td>{{ $value->name }} {{ $value->last_name }}</td>
                                                    <td>{{ $value->email }}</td>
                                                    <td>{{ $value->parent_name }} {{ $value->parent_last_name }}</td>
                                                    <td>
                                                        {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                    <td style="min-width: 100px">
                                                        <a href="{{ url('admin/parent/assign_student_parent_delete/' . $value->id) }}"
                                                            class="btn btn-danger btn-sm">Delete</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="11" style="text-align: center">Records Not Found</td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                                <div style="padding: 10px; float: right;">
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
