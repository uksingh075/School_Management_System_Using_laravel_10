@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Exam Result</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    @foreach ($getRecord as $value)
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ $value['exam_name'] }}</h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Unit test</th>
                                                <th>Half Yearly Exam</th>
                                                <th>Yearly Exam</th>
                                                <th>Total Marks</th>
                                                <th>Passsing Marks</th>
                                                <th>Full Marks</th>
                                                <th>Result</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $grand_total_marks = 0;
                                                $full_total_marks = 0;
                                                $pass_fail_check = 0;
                                            @endphp
                                            @foreach ($value['subject'] as $exam)
                                                @php
                                                    $grand_total_marks += $exam['total_marks'];
                                                    $full_total_marks += $exam['full_marks'];
                                                @endphp
                                                <tr>
                                                    <td>{{ $exam['subject_name'] }}</td>
                                                    <td>{{ $exam['unit_test'] }}</td>
                                                    <td>{{ $exam['half_yearly_exam'] }}</td>
                                                    <td>{{ $exam['yearly_exam'] }}</td>
                                                    <td>{{ $exam['total_marks'] }}</td>
                                                    <td>{{ $exam['passing_marks'] }}</td>
                                                    <td>{{ $exam['full_marks'] }}</td>
                                                    <td>
                                                        @if ($exam['total_marks'] >= $exam['passing_marks'])
                                                            <span style="color: green; font-weight: bold;">Pass</span>
                                                        @else
                                                            <span style="color: red; font-weight: bold;">Fail</span>
                                                            @php
                                                                $pass_fail_check = 1;
                                                            @endphp
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="2">
                                                    <b>Grand Total : {{ $grand_total_marks }}/{{ $full_total_marks }}</b>
                                                </td>
                                                <td colspan="2">
                                                    @php
                                                        $percentage = ($grand_total_marks * 100) / $full_total_marks;
                                                        $getGrade = App\Models\MarksGrade::getGrade($percentage);
                                                    @endphp
                                                    <b>Percentage :
                                                        {{ round($percentage, 2) }}%</b>
                                                </td>
                                                <td colspan="2">
                                                    <b>Grade :
                                                        {{ $getGrade }}</b>
                                                </td>
                                                <td colspan="2">
                                                    <b>Result :
                                                        @if ($pass_fail_check == 0)
                                                            <span style="color: green;">Pass</span>
                                                        @else
                                                            <span style="color: red;">Fail</span>
                                                        @endif
                                                    </b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection
