<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\ClassSubject;
use App\Models\ClassSubjectTimetable;
use App\Models\Subject;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClassTimetableController extends Controller
{
    public function list(Request $request)
    {
        $data['getClass'] = ClassModel::select('class.*')
            ->join('users', 'users.id', 'class.created_by')
            ->where('class.is_delete', '=', 0)
            ->where('class.status', '=', 0)
            ->orderBy('class.name', 'asc')
            ->get();

        if (!empty($request->class_id)) {
            $data['getSubject'] = ClassSubject::select('class_subject.*', 'subject.name as subject_name', 'subject.type as subject_type')
                ->join('class', 'class.id', 'class_subject.class_id')
                ->join('subject', 'subject.id', 'class_subject.subject_id')
                ->join('users', 'users.id', 'class_subject.created_by')
                ->where('class_subject.class_id', '=', $request->class_id)
                ->where('class_subject.is_delete', '=', 0)
                ->where('class_subject.status', '=', 0)
                ->orderBy('class_subject.id', 'asc')
                ->get();
        }

        $getWeek = Week::get();
        $week = array();
        foreach ($getWeek as $value) {
            $dataW = array();
            $dataW['week_id'] = $value->id;
            $dataW['week_name'] = $value->name;

            if (!empty($request->class_id) && !empty($request->subject_id)) {
                $ClassSubject =  ClassSubjectTimetable::where('class_id', '=', $request->class_id)
                    ->where('subject_id', '=', $request->subject_id)
                    ->where('week_id', '=', $value->id)->first();

                if (!empty($ClassSubject)) {
                    $dataW['start_time'] = $ClassSubject->start_time;
                    $dataW['end_time'] = $ClassSubject->end_time;
                    $dataW['room_number'] = $ClassSubject->room_number;
                } else {
                    $dataW['start_time'] = '';
                    $dataW['end_time'] = '';
                    $dataW['room_number'] = '';
                }
            } else {
                $dataW['start_time'] = '';
                $dataW['end_time'] = '';
                $dataW['room_number'] = '';
            }

            $week[] = $dataW;
        }

        $data['week'] = $week;

        $data['header_title'] = 'Class Timetable';
        return view('admin.class_timetable.list', $data);
    }

    public function get_subject(Request $request)
    {
        $getSubject = ClassSubject::select('class_subject.*', 'subject.name as subject_name', 'subject.type as subject_type')
            ->join('class', 'class.id', 'class_subject.class_id')
            ->join('subject', 'subject.id', 'class_subject.subject_id')
            ->join('users', 'users.id', 'class_subject.created_by')
            ->where('class_subject.class_id', '=', $request->class_id)
            ->where('class_subject.is_delete', '=', 0)
            ->where('class_subject.status', '=', 0)
            ->orderBy('class_subject.id', 'desc')
            ->get();
        $html = "<option value=''>Select Subject</option>";
        foreach ($getSubject as $value) {
            $html .= "<option value='" . $value->subject_id . "'>" . $value->subject_name . "</option>";
        }

        $json['html'] = $html;
        echo json_encode($json);
    }

    public function insert_update(Request $request)
    {
        ClassSubjectTimetable::where('class_id', '=', $request->class_id)
            ->where('subject_id', '=', $request->subject_id)->delete();

        foreach ($request->timetable as $timetable) {
            if (!empty($timetable['week_id']) && !empty($timetable['start_time']) && !empty($timetable['end_time']) && !empty($timetable['room_number'])) {
                $class_timetable = new ClassSubjectTimetable;
                $class_timetable->class_id = $request->class_id;
                $class_timetable->subject_id = $request->subject_id;
                $class_timetable->week_id = $timetable['week_id'];
                $class_timetable->start_time = $timetable['start_time'];
                $class_timetable->end_time = $timetable['end_time'];
                $class_timetable->room_number = $timetable['room_number'];
                $class_timetable->save();
            }
        }

        return redirect()->back()->with('success', 'Class Timetable Saved Successfully');
    }

    //Student Side
    public function myTimetable()
    {
        $result = array();
        $getRecord = ClassSubject::select('class_subject.*', 'subject.name as subject_name', 'subject.type as subject_type')
            ->join('class', 'class.id', '=', 'class_subject.class_id')
            ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
            ->join('users', 'users.id', '=', 'class_subject.created_by')
            ->where('class_subject.class_id', '=', Auth::user()->class_id)
            ->where('class_subject.is_delete', '=', 0)
            ->where('class_subject.status', '=', 0)
            ->orderBy('class_subject.id', 'asc')
            ->get();
        foreach ($getRecord as $value) {
            $dataS['name'] = $value->subject_name;

            $getWeek = Week::get();
            $week = array();
            foreach ($getWeek as $valueW) {
                $dataW = array();
                $dataW['week_id'] = $valueW->id;
                $dataW['week_name'] = $valueW->name;

                $ClassSubject =  ClassSubjectTimetable::where('class_id', '=', $value->class_id)
                    ->where('subject_id', '=', $value->subject_id)
                    ->where('week_id', '=', $valueW->id)->first();

                if (!empty($ClassSubject)) {
                    $dataW['start_time'] = $ClassSubject->start_time;
                    $dataW['end_time'] = $ClassSubject->end_time;
                    $dataW['room_number'] = $ClassSubject->room_number;
                } else {
                    $dataW['start_time'] = '';
                    $dataW['end_time'] = '';
                    $dataW['room_number'] = '';
                }

                $week[] = $dataW;
            }

            $dataS['week'] = $week;
            $result[] = $dataS;
        }

        $data['getRecord'] = $result;

        $data['header_title'] = 'My Timetable';
        return view('student.mytimetable', $data);
    }

    //Teacher Side
    public function myTimetableTeacher($class_id, $subject_id)
    {

        $data['getClass'] = ClassModel::find($class_id);
        $data['getSubject'] = Subject::find($subject_id);

        $getWeek = Week::get();
        $week = array();
        foreach ($getWeek as $valueW) {
            $dataW = array();
            $dataW['week_id'] = $valueW->id;
            $dataW['week_name'] = $valueW->name;

            $ClassSubject =  ClassSubjectTimetable::where('class_id', '=', $class_id)
                ->where('subject_id', '=', $subject_id)
                ->where('week_id', '=', $valueW->id)->first();

            if (!empty($ClassSubject)) {
                $dataW['start_time'] = $ClassSubject->start_time;
                $dataW['end_time'] = $ClassSubject->end_time;
                $dataW['room_number'] = $ClassSubject->room_number;
            } else {
                $dataW['start_time'] = '';
                $dataW['end_time'] = '';
                $dataW['room_number'] = '';
            }

            $week[] = $dataW;
        }

        $data['getRecord'] = $week;

        $data['header_title'] = 'My Timetable';
        return view('teacher.mytimetable', $data);
    }

    //Parent Side

    public function myTimetableParent($class_id,$subject_id)
    {

        $data['getClass'] = ClassModel::find($class_id);
        $data['getSubject'] = Subject::find($subject_id);

        $getWeek = Week::get();
        $week = array();
        foreach ($getWeek as $valueW) {
            $dataW = array();
            $dataW['week_id'] = $valueW->id;
            $dataW['week_name'] = $valueW->name;

            $ClassSubject =  ClassSubjectTimetable::where('class_id', '=', $class_id)
                ->where('subject_id', '=', $subject_id)
                ->where('week_id', '=', $valueW->id)->first();

            if (!empty($ClassSubject)) {
                $dataW['start_time'] = $ClassSubject->start_time;
                $dataW['end_time'] = $ClassSubject->end_time;
                $dataW['room_number'] = $ClassSubject->room_number;
            } else {
                $dataW['start_time'] = '';
                $dataW['end_time'] = '';
                $dataW['room_number'] = '';
            }

            $week[] = $dataW;
        }

        $data['getRecord'] = $week;

        $data['header_title'] = 'My Timetable';
        return view('parent.mytimetable', $data);
    }

}
