@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add New Homework</h1>
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
                                    <div class="form-group">
                                        <label>Class</label>
                                        <select name="class_id" id="getClass"
                                            class="form-control @error('class_id') is-invalid @enderror">
                                            <option value="">Select Class</option>
                                            @foreach ($getClass as $class)
                                                <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                    value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('class_id')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Subject</label>
                                        <select name="subject_id" id="getSubject"
                                            class="form-control @error('subject_id') is-invalid @enderror">
                                            <option value="">Select Subject</option>
                                        </select>
                                        @error('subject_id')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Homework Date</label>
                                        <input type="date" name="homework_date"
                                            class="form-control @error('homework_date') is-invalid @enderror">
                                        @error('homework_date')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Submission Date</label>
                                        <input type="date" name="submission_date"
                                            class="form-control @error('submission_date') is-invalid @enderror">
                                        @error('submission_date')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Document</label>
                                        <input type="file" name="document"
                                            class="form-control @error('document') is-invalid @enderror">
                                        @error('document')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea id="compose-textarea" name="description" class="form-control @error('description') is-invalid @enderror"
                                            style="height: 300px">{{ old('description') }}</textarea>
                                        @error('description')
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

@section('script')
    <script>
        $(function() {

            $('#compose-textarea').summernote({
                height: 200
            });

            $('#getClass').change(function() {
                var class_id = $(this).val();
                $.ajax({
                    url: "{{ url('admin/homework/getsubject') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        class_id: class_id,
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#getSubject').html(response.success);
                    }
                });
            });
        })
    </script>
@endsection
