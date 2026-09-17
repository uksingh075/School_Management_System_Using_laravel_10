@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Student List </h1>
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
                                <h3 class="card-title">My Student List</h3>
                            </div>
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
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
                                                        {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="17" style="text-align: center">Records Not Found</td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                                <div style="padding: 10px; float: right;">
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
