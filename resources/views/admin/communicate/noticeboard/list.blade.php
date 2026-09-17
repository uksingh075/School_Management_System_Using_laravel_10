@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Notice Board List (Total : {{ $getRecord->total() }})</h1>
                    </div>
                    <div class="col-sm-6" style="text-align: right">
                        <a href="{{ URL::to('/') }}/admin/communicate/notice_board/add" class="btn btn-primary">Add New
                            Notice Board</a>
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
                                <h3 class="card-title">Search Notice Board</h3>
                            </div>
                            <form action="" method="get">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Title</label>
                                            <input type="text" class="form-control" name="title"
                                                placeholder="Enter title" value="{{ Request::get('title') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>From Notice Date</label>
                                            <input type="date" class="form-control" name="from_notice_date"
                                                value="{{ Request::get('from_notice_date') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>To Notice Date</label>
                                            <input type="date" class="form-control" name="to_notice_date"
                                                value="{{ Request::get('to_notice_date') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>From Publish Date</label>
                                            <input type="date" class="form-control" name="from_publish_date"
                                                value="{{ Request::get('from_publish_date') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>To Publish Date</label>
                                            <input type="date" class="form-control" name="to_publish_date"
                                                value="{{ Request::get('to_publish_date') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Message To</label>
                                            <select name="message_to" class="form-control">
                                                <option value="">Select Message To</option>
                                                <option {{ Request::get('message_to') == 3 ? 'selected' : '' }}
                                                    value="3">Student</option>
                                                <option {{ Request::get('message_to') == 4 ? 'selected' : '' }}
                                                    value="4">Parent</option>
                                                <option {{ Request::get('message_to') == 2 ? 'selected' : '' }}
                                                    value="2">Teacher</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-primary"
                                                style="margin-top: 30px;">Search</button>
                                            <a href="{{ url('admin/communicate/notice_board') }}" class="btn btn-success"
                                                style="margin-top: 30px;">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @include('message')
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Notice Board List</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Notice Date</th>
                                            <th>Publish Date</th>
                                            <th>Message To</th>
                                            <th>Created By</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value->title }}</td>
                                                <td>{{ date('d-m-Y', strtotime($value->notice_date)) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($value->publish_date)) }}</td>
                                                <td>
                                                    @foreach ($value->getMessage as $message)
                                                        @if ($message->message_to == 2)
                                                            <div>Teacher</div>
                                                        @elseif($message->message_to == 3)
                                                            <div>Student</div>
                                                        @elseif($message->message_to == 4)
                                                            <div>Parent</div>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{{ $value->created_by_name }}</td>
                                                <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                <td>
                                                    <a href="{{ url('admin/communicate/notice_board/edit/' . $value->id) }}"
                                                        class="btn btn-primary">Edit</a>
                                                    <a href="{{ url('admin/communicate/notice_board/delete/' . $value->id) }}"
                                                        class="btn btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" style="text-align: center">Records Not Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div style="padding: 10px; float:right;">
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
