@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add New Student</h1>
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
                                                name="name" placeholder="Enter First Name" value="{{ old('name') }}">
                                            @error('name')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Last Name <span style="color:red;">*</span></label>
                                            <input type="text"
                                                class="form-control @error('last_name') is-invalid @enderror"
                                                name="last_name" placeholder="Enter Last Name"
                                                value="{{ old('last_name') }}">
                                            @error('last_name')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Admission Number <span style="color:red;">*</span></label>
                                            <input type="text"
                                                class="form-control @error('admission_number') is-invalid @enderror"
                                                name="admission_number" placeholder="Enter Admission Number"
                                                value="{{ old('admission_number') }}">
                                            @error('admission_number')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Roll Number <span style="color:red;">*</span></label>
                                            <input type="text"
                                                class="form-control @error('roll_number') is-invalid @enderror"
                                                name="roll_number" placeholder="Enter Roll Number"
                                                value="{{ old('roll_number') }}">
                                            @error('roll_number')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Class <span style="color:red;">*</span></label>
                                            <select class="form-control @error('class_id') is-invalid @enderror"
                                                name="class_id">
                                                <option value="">Select Class</option>
                                                @foreach ($getClass as $value)
                                                    <option {{ old('class_id') == $value->id ? 'selected' : '' }}
                                                        value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('class_id')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Gender <span style="color:red;">*</span></label>
                                            <select class="form-control @error('gender') is-invalid @enderror"
                                                name="gender">
                                                <option value="">Select Gender</option>
                                                <option {{ old('gender') == 'Male' ? 'selected' : '' }} value="Male">
                                                    Male</option>
                                                <option {{ old('gender') == 'Female' ? 'selected' : '' }} value="Female">
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
                                                name="date_of_birth" value="{{ old('date_of_birth') }}">
                                            @error('date_of_birth')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Mobile Number <span style="color:red;">*</span></label>
                                            <input type="text"
                                                class="form-control @error('mobile_number') is-invalid @enderror"
                                                name="mobile_number" placeholder="Enter Mobile Number"
                                                value="{{ old('mobile_number') }}">
                                            @error('mobile_number')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Admission Date <span style="color:red;">*</span></label>
                                            <input type="date"
                                                class="form-control @error('admission_date') is-invalid @enderror"
                                                name="admission_date" value="{{ old('admission_date') }}">
                                            @error('admission_date')
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
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Blood Group <span style="color:red;"></span></label>
                                            <input type="text"
                                                class="form-control @error('blood_group') is-invalid @enderror"
                                                name="blood_group" placeholder="Enter Blood Group"
                                                value="{{ old('blood_group') }}">
                                            @error('blood_group')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Height<span style="color:red;">*</span></label>
                                            <input type="text"
                                                class="form-control @error('height') is-invalid @enderror" name="height"
                                                placeholder="Enter Height" value="{{ old('height') }}">
                                            @error('height')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Weight <span style="color:red;">*</span></label>
                                            <input type="text"
                                                class="form-control @error('weight') is-invalid @enderror" name="weight"
                                                placeholder="Enter Weight" value="{{ old('weight') }}">
                                            @error('weight')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Status <span style="color:red;">*</span></label>
                                            <select class="form-control" name="status">
                                                <option {{ old('status') == 0 ? 'selected' : '' }} value="0">Active
                                                </option>
                                                <option {{ old('status') == 1 ? 'selected' : '' }} value="1">
                                                    Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label>Email address <span style="color:red;">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" placeholder="Enter Email" value="{{ old('email') }}">
                                        @error('email')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Password <span style="color:red;">*</span></label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            placeholder="Enter Password">
                                        @error('password')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
