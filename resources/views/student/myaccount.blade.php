@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Account</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @include('message')
                        <div class="card card-primary">
                            <form action="" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>First Name <span style="color:red;">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="name" placeholder="Enter First Name"
                                                value="{{ old('name', $getRecord->name) }}">
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
                                                <option {{ old('gender', $getRecord->gender) == 'Male' ? 'selected' : '' }}
                                                    value="Male">
                                                    Male</option>
                                                <option
                                                    {{ old('gender', $getRecord->gender) == 'Female' ? 'selected' : '' }}
                                                    value="Female">
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
                                                name="date_of_birth"
                                                value="{{ old('date_of_birth', $getRecord->date_of_birth) }}">
                                            @error('date_of_birth')
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
                                            <label>Profile Pic <span style="color:red;">*</span></label>
                                            <input type="file"
                                                class="form-control @error('profile_pic') is-invalid @enderror"
                                                name="profile_pic">
                                            @error('profile_pic')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                            @if (!empty($getRecord->getProfile()))
                                                <img src="{{ $getRecord->getProfile() }}"
                                                    style="width: auto; height: 50px;" alt="img">
                                            @endif
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Blood Group <span style="color:red;"></span></label>
                                            <input type="text"
                                                class="form-control @error('blood_group') is-invalid @enderror"
                                                name="blood_group" placeholder="Enter Blood Group"
                                                value="{{ old('blood_group', $getRecord->blood_group) }}">
                                            @error('blood_group')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Height<span style="color:red;">*</span></label>
                                            <input type="text" class="form-control @error('height') is-invalid @enderror"
                                                name="height" placeholder="Enter Height"
                                                value="{{ old('height', $getRecord->height) }}">
                                            @error('height')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Weight <span style="color:red;">*</span></label>
                                            <input type="text"
                                                class="form-control @error('weight') is-invalid @enderror" name="weight"
                                                placeholder="Enter Weight"
                                                value="{{ old('weight', $getRecord->weight) }}">
                                            @error('weight')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
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
