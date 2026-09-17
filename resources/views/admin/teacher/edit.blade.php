@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Teacher</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <form action="" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>First Name <span style="color:red;">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="name" placeholder="Enter First Name" value="{{ old('name', $getRecord->name) }}">
                                            @error('name')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Last Name <span style="color:red;">*</span></label>
                                            <input type="text"
                                                class="form-control @error('last_name') is-invalid @enderror"
                                                name="last_name" placeholder="Enter Last Name"
                                                value="{{ old('last_name', $getRecord->last_name) }}">
                                            @error('last_name')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Gender <span style="color:red;">*</span></label>
                                            <select class="form-control @error('gender') is-invalid @enderror"
                                                name="gender">
                                                <option value="">Select Gender</option>
                                                <option {{ old('gender', $getRecord->gender) == 'Male' ? 'selected' : '' }} value="Male">
                                                    Male</option>
                                                <option {{ old('gender', $getRecord->gender) == 'Female' ? 'selected' : '' }} value="Female">
                                                    Female</option>
                                            </select>
                                            @error('gender')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Date Of Birth <span style="color:red;">*</span></label>
                                            <input type="date"
                                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                                name="date_of_birth" value="{{ old('date_of_birth', $getRecord->date_of_birth) }}">
                                            @error('date_of_birth')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Date of Joining <span style="color:red;">*</span></label>
                                            <input type="date"
                                                class="form-control @error('admission_date') is-invalid @enderror"
                                                name="admission_date" value="{{ old('admission_date', $getRecord->admission_date) }}">
                                            @error('admission_date')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Mobile Number <span style="color:red;">*</span></label>
                                            <input type="text"
                                                class="form-control @error('mobile_number') is-invalid @enderror"
                                                name="mobile_number" placeholder="Enter Mobile Number"
                                                value="{{ old('mobile_number', $getRecord->mobile_number) }}">
                                            @error('mobile_number')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Marital Status <span style="color:red;">*</span></label>
                                            <input type="text"
                                                class="form-control @error('marital_status') is-invalid @enderror"
                                                name="marital_status" placeholder="Enter Marital Status"
                                                value="{{ old('marital_status', $getRecord->marital_status) }}">
                                            @error('marital_status')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Profile Pic <span style="color:red;">*</span></label>
                                            <input type="file"
                                                class="form-control @error('profile_pic') is-invalid @enderror"
                                                name="profile_pic">
                                            @error('profile_pic')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                            @if(!empty($getRecord->getProfile()))
                                                <img src="{{ $getRecord->getProfile() }}" style="width: auto; height: 50px;" alt="img">
                                            @endif
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Current Address <span style="color:red;">*</span></label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" name="address"
                                                placeholder="Enter Current Address">{{ old('address', $getRecord->address) }}</textarea>
                                            @error('address')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Permanent Address <span style="color:red;">*</span></label>
                                            <textarea class="form-control @error('permanent_address') is-invalid @enderror" name="permanent_address"
                                                placeholder="Enter Permanent Address">{{ old('permanent_address', $getRecord->permanent_address) }}</textarea>
                                            @error('permanent_address')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Qualification<span style="color:red;">*</span></label>
                                            <textarea class="form-control @error('qualification') is-invalid @enderror" name="qualification"
                                                placeholder="Enter Qualification">{{ old('qualification', $getRecord->qualification) }}</textarea>
                                            @error('qualification')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Work Experience <span style="color:red;">*</span></label>
                                            <textarea class="form-control @error('work_experience') is-invalid @enderror" name="work_experience"
                                                placeholder="Enter Work Experience">{{ old('work_experience', $getRecord->work_experience) }}</textarea>
                                            @error('work_experience')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Status <span style="color:red;">*</span></label>
                                            <select class="form-control" name="status">
                                                <option {{ old('status', $getRecord->status) == 0 ? 'selected' : '' }} value="0">Active
                                                </option>
                                                <option {{ old('status', $getRecord->status) == 1 ? 'selected' : '' }} value="1">
                                                    Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label>Email address <span style="color:red;">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" placeholder="Enter Email"
                                            value="{{ old('email', $getRecord->email) }}">
                                        @error('email')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            placeholder="Enter Password">
                                        <p>Due you want to change password so Please add new password</p>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
