@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Class Timetable </h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @foreach ($getRecord as $value)
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ $value['name'] }}</h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Week</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Room Number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($value['week'] as $valueW)
                                                @if ($valueW['week_id'] == 7)
                                                    <tr>
                                                        <th colspan="4" style="text-align: center;">
                                                            <h4>HoliDay</h4>
                                                        </th>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>{{ $valueW['week_name'] }}</td>
                                                        <td>{{ !empty($valueW['start_time']) ? date('h:i A', strtotime($valueW['start_time'])) : '' }}
                                                        </td>
                                                        <td>{{ !empty($valueW['end_time']) ? date('h:i A', strtotime($valueW['end_time'])) : '' }}
                                                        </td>
                                                        <td>{{ $valueW['room_number'] }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
