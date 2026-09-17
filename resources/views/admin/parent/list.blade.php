@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Parent List (Total : {{ $getRecord->total() }})</h1>

                    </div>
                    <div class="col-sm-6" style="text-align: right">
                        <a href="{{ URL::to('/') }}/admin/parent/add" class="btn btn-primary">Add New Student</a>
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
                                <h3 class="card-title">Search Parent</h3>
                            </div>
                            <form action="" method="get">
                                <div class="card-body">
                                    <div class="row">
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
                                        <div class="form-group col-md-2">
                                            <label>Gender</label>
                                            <select class="form-control" name="gender">
                                                <option value="">Select Gender</option>
                                                <option {{ Request::get('gender') == 'Male' ? 'selected' : '' }}
                                                    value="Male">
                                                    Male</option>
                                                <option {{ Request::get('gender') == 'Female' ? 'selected' : '' }}
                                                    value="Female">
                                                    Female</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Occupation</label>
                                            <input type="text" class="form-control" name="occupation"
                                                placeholder="Enter occupation" value="{{ Request::get('occupation') }}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Address</label>
                                            <input type="text" class="form-control" name="address"
                                                placeholder="Enter address" value="{{ Request::get('address') }}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Mobile Number</label>
                                            <input type="text" class="form-control" name="mobile_number"
                                                placeholder="Enter mobile number"
                                                value="{{ Request::get('mobile_number') }}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Status</label>
                                            <select class="form-control" name="status">
                                                <option value="">Select Status</option>
                                                <option {{ Request::get('status') == 100 ? 'selected' : '' }}
                                                    value="100">Active
                                                </option>
                                                <option {{ Request::get('status') == 1 ? 'selected' : '' }} value="1">
                                                    Inactive</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Created Date</label>
                                            <input type="date" class="form-control" name="date"
                                                value="{{ Request::get('date') }}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-primary"
                                                style="margin-top: 30px;">Search</button>
                                            <a href="{{ url('admin/parent/list') }}" class="btn btn-success"
                                                style="margin-top: 30px;">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @include('message')
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Parent List</h3>
                            </div>
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Profile Pic</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Gender</th>
                                            <th>Mobile Number</th>
                                            <th>Occupation</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @forelse ($getRecord as $value)
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
                                                    <td>{{ $value->gender }}</td>
                                                    <td>{{ $value->mobile_number }}</td>
                                                    <td>{{ $value->occupation }}</td>
                                                    <td>{{ $value->address }}</td>
                                                    <td>{{ $value->status == 0 ? 'Active' : 'Inactive' }}</td>
                                                    <td>
                                                        {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                    <td style="min-width: 300px">
                                                        <a href="{{ url('admin/parent/edit/' . $value->id) }}"
                                                            class="btn btn-primary">Edit</a>
                                                        <a href="{{ url('admin/parent/delete/' . $value->id) }}"
                                                            class="btn btn-danger">Delete</a>
                                                        <a href="{{ url('admin/parent/my-student/' . $value->id) }}"
                                                            class="btn btn-primary">My Student</a>
                                                    </td>
                                                </tr>
                                                @empty
                                            <tr>
                                                <td colspan="100%" style="text-align: center">Records Not Found</td>
                                            </tr>
                                            @endforelse
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
