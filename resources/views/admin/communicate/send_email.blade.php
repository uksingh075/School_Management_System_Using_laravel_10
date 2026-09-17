@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ URL::to('/') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ URL::to('/') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <style type="text/css">
        .select2-container .select2-selection--single {
            height: 40px;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Send Email</h1>
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
                            <form action="" method="post">
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Subject</label>
                                        <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                            name="subject" placeholder="Enter subject" value="{{ old('subject') }}">
                                        @error('subject')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>User (Student / Parent / Teacher)</label>
                                        <select name="user_id" class="form-control @error('user_id') is-invalid @enderror select2" style="width: 100%;">
                                            <option value="">Select User</option>
                                        </select>
                                        @error('user_id')
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
                                    <button type="submit" class="btn btn-primary">Send Email</button>
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
    <script src="{{ URL::to('/') }}/plugins/select2/js/select2.full.min.js"></script>
    <script>
        $(function() {

            $('.select2').select2({
                ajax: {
                    url: "{{ url('admin/communicate/search_user') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(data) {
                        return {
                            search: data.term,
                        }
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                }
            });

            $('#compose-textarea').summernote({
                height: 200
            });
        })
    </script>
@endsection
