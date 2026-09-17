@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Marks Grade</h1>
                    </div>
                    <div class="col-sm-6" style="text-align: right">
                        <a href="{{ URL::to('/') }}/admin/examinations/marks_grade/add" class="btn btn-primary">Add New Marks Grade</a>
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
                                <h3 class="card-title">Marks Grade</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Grade Name</th>
                                            <th>Percent From</th>
                                            <th>Percent To</th>
                                            <th>Created By</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($getRecord->isNotEmpty())
                                            @foreach ($getRecord as $value)
                                                <tr>
                                                    <td>{{ $value->name }}</td>
                                                    <td>{{ $value->percent_from }}</td>
                                                    <td>{{ $value->percent_to }}</td>
                                                    <td>{{ $value->created_by_name }}</td>
                                                    <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                    <td>
                                                        <a href="{{ url('admin/examinations/marks_grade/edit/' . $value->id) }}"
                                                            class="btn btn-primary">Edit</a>
                                                        <a href="{{ url('admin/examinations/marks_grade/delete/' . $value->id) }}"
                                                            class="btn btn-danger">Delete</a>
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
