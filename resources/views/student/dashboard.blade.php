@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>₹{{ number_format($TotalFeesPaidAmount, 2) }}</h3>
                                <p>Total Fees Paid Amount</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ url('student/fees_collection') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $TotalSubject }}</h3>
                                <p>Total Subject</p>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-table"></i>
                            </div>
                            <a href="{{ url('student/my_subject') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $getTotalNotice }}</h3>
                                <p>Total Notice</p>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-table"></i>
                            </div>
                            <a href="{{ url('student/my_notice_board') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $getTotalHomework }}</h3>
                                <p>Total Homework</p>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-table"></i>
                            </div>
                            <a href="{{ url('student/my_homework') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $getTotalSubmittedHomework }}</h3>
                                <p>Total Submitted Homework</p>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-table"></i>
                            </div>
                            <a href="{{ url('student/my_submitted_homework') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $getTotalAttendance }}</h3>
                                <p>Total Attendance</p>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-table"></i>
                            </div>
                            <a href="{{ url('student/my_attendance') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
