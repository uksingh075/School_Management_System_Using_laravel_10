@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add New Marks Grade</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <form action="" method="post">
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Grade Name</label>
                                        <input type="text" class="form-control @error('grade_name') is-invalid @enderror"
                                            name="grade_name" placeholder="Enter grade name" value="{{ old('grade_name') }}">
                                        @error('grade_name')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Percent From</label>
                                        <input type="number" class="form-control @error('percent_from') is-invalid @enderror"
                                            name="percent_from" placeholder="Enter percent from" value="{{ old('percent_from') }}">
                                        @error('percent_from')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Percent To</label>
                                        <input type="number" class="form-control @error('percent_to') is-invalid @enderror"
                                            name="percent_to" placeholder="Enter percent to" value="{{ old('percent_to') }}">
                                        @error('percent_to')
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
