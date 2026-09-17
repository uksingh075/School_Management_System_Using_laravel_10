@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add New Notice Board</h1>
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
                                        <label>Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="title" placeholder="Enter title" value="{{ old('title') }}">
                                        @error('title')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Notice Date</label>
                                        <input type="date"
                                            class="form-control @error('notice_date') is-invalid @enderror"
                                            name="notice_date" value="{{ old('notice_date') }}">
                                        @error('notice_date')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Publish Date</label>
                                        <input type="date"
                                            class="form-control @error('publish_date') is-invalid @enderror"
                                            name="publish_date" value="{{ old('publish_date') }}">
                                        @error('publish_date')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label style="display: block;">Message To</label>
                                        <label style="margin-right: 20px;"><input type="checkbox" value="3"
                                                name="message_to[]"> Student</label>
                                        <label style="margin-right: 20px;"><input type="checkbox" value="4"
                                                name="message_to[]"> Parent</label>
                                        <label><input type="checkbox" value="2" name="message_to[]"> Teacher</label>
                                    </div>

                                    <div class="form-group">
                                        <label>Message</label>
                                        <textarea id="compose-textarea" name="message" class="form-control @error('message') is-invalid @enderror"
                                            style="height: 300px">{{ old('message') }}</textarea>
                                        @error('message')
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
        })
    </script>
@endsection
