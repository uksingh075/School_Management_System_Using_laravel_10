@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Marks Register</h1>
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
                                <h3 class="card-title">Search Marks Register</h3>
                            </div>
                            <form action="" method="get">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Exam</label>
                                            <select name="exam_id" class="form-control">
                                                <option value="">Select Exam</option>
                                                @foreach ($getExam as $exam)
                                                    <option
                                                        {{ Request::get('exam_id') == $exam->exam_id ? 'selected' : '' }}
                                                        value="{{ $exam->exam_id }}">{{ $exam->exam_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Class</label>
                                            <select name="class_id" class="form-control">
                                                <option value="">Select Class</option>
                                                @foreach ($getClass as $class)
                                                    <option
                                                        {{ Request::get('class_id') == $class->class_id ? 'selected' : '' }}
                                                        value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-primary"
                                                style="margin-top: 30px;">Search</button>
                                            <a href="{{ url('teacher/marks_register') }}" class="btn btn-success"
                                                style="margin-top: 30px;">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @include('message')
                        @if (!empty($getSubject) && !empty($getSubject->count()))
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Marks Register</h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped" style="overflow: auto;">
                                        <thead>
                                            <tr>
                                                <th>STUDENT NAME</th>
                                                @foreach ($getSubject as $subject)
                                                    <th>
                                                        {{ $subject->subject_name }} <br> ({{ $subject->subject_type }} :
                                                        {{ $subject->passing_marks }} /
                                                        {{ $subject->full_marks }})
                                                    </th>
                                                @endforeach
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($getStudent) && !empty($getStudent->count()))
                                                @foreach ($getStudent as $student)
                                                    <form name="post" class="SubmitForm">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="student_id"
                                                            value="{{ $student->id }}">
                                                        <input type="hidden" name="exam_id"
                                                            value="{{ Request::get('exam_id') }}">
                                                        <input type="hidden" name="class_id"
                                                            value="{{ Request::get('class_id') }}">
                                                        <tr>
                                                            <td>{{ $student->name }} {{ $student->last_name }}</td>
                                                            @php
                                                                $i = 1;
                                                                $totalStudentMarks = 0;
                                                                $totalSubjectMarks = 0;
                                                                $totalPassingMarks = 0;
                                                                $pass_fail_check = 0;
                                                            @endphp
                                                            @foreach ($getSubject as $subject)
                                                                @php
                                                                    $totalmarks = 0;
                                                                    $totalSubjectMarks += $subject->full_marks;
                                                                    $totalPassingMarks += $subject->passing_marks;
                                                                    $getMark = $subject->getMark(
                                                                        $student->id,
                                                                        Request::get('exam_id'),
                                                                        Request::get('class_id'),
                                                                        $subject->subject_id,
                                                                    );
                                                                    if (!empty($getMark)) {
                                                                        $totalmarks =
                                                                            $getMark->unit_test +
                                                                            $getMark->half_yearly_exam +
                                                                            $getMark->yearly_exam;
                                                                    }
                                                                    $totalStudentMarks += $totalmarks;
                                                                @endphp
                                                                <td>
                                                                    <div style="margin-bottom: 10px;">
                                                                        Unit Test
                                                                        <input type="hidden"
                                                                            name="marks[{{ $i }}][full_marks]"
                                                                            value="{{ $subject->full_marks }}">
                                                                        <input type="hidden"
                                                                            name="marks[{{ $i }}][passing_marks]"
                                                                            value="{{ $subject->passing_marks }}">
                                                                        <input type="hidden"
                                                                            name="marks[{{ $i }}][id]"
                                                                            value="{{ $subject->id }}">
                                                                        <input type="hidden"
                                                                            name="marks[{{ $i }}][subject_id]"
                                                                            value="{{ $subject->subject_id }}">
                                                                        <input type="text"
                                                                            name="marks[{{ $i }}][unit_test]"
                                                                            id="unit_test_{{ $student->id }}{{ $subject->subject_id }}"
                                                                            style="width: 200px;" placeholder="Enter Marks"
                                                                            value="{{ !empty($getMark->unit_test) ? $getMark->unit_test : '' }}"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div style="margin-bottom: 10px;">
                                                                        Half Yearly Exam
                                                                        <input type="text"
                                                                            id="half_yearly_exam_{{ $student->id }}{{ $subject->subject_id }}"
                                                                            name="marks[{{ $i }}][half_yearly_exam]"
                                                                            style="width: 200px;" placeholder="Enter Marks"
                                                                            value="{{ !empty($getMark->half_yearly_exam) ? $getMark->half_yearly_exam : '' }}"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div style="margin-bottom: 10px;">
                                                                        Yearly Exam
                                                                        <input type="text"
                                                                            id="yearly_exam_{{ $student->id }}{{ $subject->subject_id }}"
                                                                            name="marks[{{ $i }}][yearly_exam]"
                                                                            style="width: 200px;" placeholder="Enter Marks"
                                                                            value="{{ !empty($getMark->yearly_exam) ? $getMark->yearly_exam : '' }}"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div style="margin-bottom: 10px;">
                                                                        <button type="button"
                                                                            class="btn btn-primary SaveSingleSubject"
                                                                            id="{{ $student->id }}"
                                                                            data-val="{{ $subject->subject_id }}"
                                                                            data-exam="{{ Request::get('exam_id') }}"
                                                                            data-class="{{ Request::get('class_id') }}"
                                                                            data-schedule="{{ $subject->id }}">Save</button>
                                                                    </div>
                                                                    @if (!empty($getMark))
                                                                        <div style="margin-bottom: 10px;">
                                                                            <b>Total Marks :</b> {{ $totalmarks }} <br>
                                                                            <b>Passing Marks :</b>
                                                                            {{ $subject->passing_marks }} <br>
                                                                            @php
                                                                                $getSubjectGrade = App\Models\MarksGrade::getGrade($totalmarks);
                                                                            @endphp
                                                                            @if (!empty($getSubjectGrade))
                                                                                <b>Grade : </b> {{ $getSubjectGrade }}
                                                                                <br>
                                                                            @endif
                                                                            @if ($totalmarks >= $subject->passing_marks)
                                                                                <b>Result : </b><span
                                                                                    style="color: green; font-weight: bold;">Pass</span>
                                                                            @else
                                                                                <b>Result : </b><span
                                                                                    style="color: red; font-weight: bold;">Fail</span>
                                                                                @php
                                                                                    $pass_fail_check = 1;
                                                                                @endphp
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                                @php
                                                                    $i++;
                                                                @endphp
                                                            @endforeach
                                                            <td style="min:width: 150px;">
                                                                <button type="submit" class="btn btn-success">Save</button>
                                                                <br><br>
                                                                @if (!empty($totalStudentMarks))
                                                                    <b>Total Subject Marks :</b> {{ $totalSubjectMarks }}
                                                                    <br>
                                                                    <b>Total Passing Marks :</b> {{ $totalPassingMarks }}
                                                                    <br>
                                                                    <b>Total Student Marks :</b> {{ $totalStudentMarks }}
                                                                    <br>
                                                                    @php
                                                                        $percentage =
                                                                            ($totalStudentMarks * 100) /
                                                                            $totalSubjectMarks;

                                                                        $getGrade = App\Models\MarksGrade::getGrade($percentage);
                                                                    @endphp
                                                                    @if ($pass_fail_check == 0)
                                                                        <b>Percentage : </b> {{ round($percentage, 2) }}%
                                                                        <br>
                                                                        @if (!empty($getGrade))
                                                                            <b>Grade : </b> {{ $getGrade }}
                                                                            <br>
                                                                        @endif
                                                                        <b>Result : </b><span
                                                                            style="color: green; font-weight: bold;">Pass</span>
                                                                    @else
                                                                        <b>Result : </b><span
                                                                            style="color: red; font-weight: bold;">Fail</span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </form>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('.SubmitForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url('teacher/submit_marks_register') }}",
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    location.reload();
                }
            });
        });

        $('.SaveSingleSubject').click(function(e) {
            var student_id = $(this).attr('id');
            var subject_id = $(this).attr('data-val');
            var exam_id = $(this).attr('data-exam');
            var class_id = $(this).attr('data-class');
            var id = $(this).attr('data-schedule');

            var unit_test = $('#unit_test_' + student_id + subject_id).val();
            var half_yearly_exam = $('#half_yearly_exam_' + student_id + subject_id).val();
            var yearly_exam = $('#yearly_exam_' + student_id + subject_id).val();

            $.ajax({
                url: "{{ url('teacher/single_submit_marks_register') }}",
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    id: id,
                    student_id: student_id,
                    subject_id: subject_id,
                    exam_id: exam_id,
                    class_id: class_id,
                    unit_test: unit_test,
                    half_yearly_exam: half_yearly_exam,
                    yearly_exam: yearly_exam,
                },
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    location.reload();
                }
            });
        });
    </script>
@endsection
