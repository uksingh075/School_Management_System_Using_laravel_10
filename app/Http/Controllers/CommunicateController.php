<?php

namespace App\Http\Controllers;

use App\Mail\SendEmailUserMail;
use App\Models\NoticeBoard;
use App\Models\NoticeBoardMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CommunicateController extends Controller
{
    public function NoticeBoard(Request $request)
    {
        $return = NoticeBoard::select('notice_board.*', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'notice_board.created_by');

        if (!empty($request->title)) {
            $return = $return->where('notice_board.title', 'LIKE', '%' . $request->title . '%');
        }

        if (!empty($request->from_notice_date)) {
            $return = $return->whereDate('notice_board.notice_date', '>=', $request->from_notice_date);
        }

        if (!empty($request->to_notice_date)) {
            $return = $return->whereDate('notice_board.notice_date', '<=', $request->to_notice_date);
        }

        if (!empty($request->from_publish_date)) {
            $return = $return->whereDate('notice_board.publish_date', '>=', $request->from_publish_date);
        }

        if (!empty($request->to_publish_date)) {
            $return = $return->whereDate('notice_board.publish_date', '<=', $request->to_publish_date);
        }

        if (!empty($request->message_to)) {

            $return = $return->join('notice_board_message', 'notice_board_message.notice_board_id', '=', 'notice_board.id');

            $return = $return->where('notice_board_message.message_to', '=', $request->message_to);
        }

        $return = $return->orderBy('notice_board.id', 'desc')
            ->paginate(20);

        $data['getRecord'] = $return;
        $data['header_title'] = 'Notice Board';
        return view('admin.communicate.noticeboard.list', $data);
    }

    public function AddNoticeBoard()
    {
        $data['header_title'] = 'Add New Notice Board';
        return view('admin.communicate.noticeboard.add', $data);
    }

    public function InsertNoticeBoard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:30|regex:/^[a-zA-Z0-9\s+.]+$/',
            'notice_date' => 'required',
            'publish_date' => 'required',
            'message' => 'required',

        ]);

        if ($validator->passes()) {

            $notice = new NoticeBoard;
            $notice->title = $request->title;
            $notice->notice_date = $request->notice_date;
            $notice->publish_date = $request->publish_date;
            $notice->message = $request->message;
            $notice->created_by = Auth::user()->id;
            $notice->save();

            if (!empty($request->message_to)) {
                foreach ($request->message_to as $message_to) {
                    $message = new NoticeBoardMessage;
                    $message->notice_board_id = $notice->id;
                    $message->message_to = $message_to;
                    $message->save();
                }
            }

            return redirect('admin/communicate/notice_board')->with('success', 'Notice Board added successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function EditNoticeBoard($id)
    {
        $data['getRecord'] = NoticeBoard::find($id);
        $data['header_title'] = 'Edit Notice Board';
        return view('admin.communicate.noticeboard.edit', $data);
    }

    public function UpdateNoticeBoard($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:30|regex:/^[a-zA-Z0-9\s+.]+$/',
            'notice_date' => 'required',
            'publish_date' => 'required',
            'message' => 'required',

        ]);

        if ($validator->passes()) {

            $notice = NoticeBoard::find($id);
            $notice->title = $request->title;
            $notice->notice_date = $request->notice_date;
            $notice->publish_date = $request->publish_date;
            $notice->message = $request->message;
            $notice->save();

            NoticeBoardMessage::where('notice_board_id', '=', $id)->delete();

            if (!empty($request->message_to)) {
                foreach ($request->message_to as $message_to) {
                    $message = new NoticeBoardMessage;
                    $message->notice_board_id = $notice->id;
                    $message->message_to = $message_to;
                    $message->save();
                }
            }

            return redirect('admin/communicate/notice_board')->with('success', 'Notice Board updated successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function DeleteNoticeBoard($id)
    {
        $notice = NoticeBoard::find($id);
        $notice->delete();

        NoticeBoardMessage::where('notice_board_id', '=', $id)->delete();

        return redirect()->back()->with('success', 'Notice Board deleted successfully');
    }

    public function SendEmail()
    {
        $data['header_title'] = 'Send Email';
        return view('admin.communicate.send_email', $data);
    }

    public function SearchUser(Request $request)
    {
        $json = array();
        if (!empty($request->search)) {
            $getUser = User::select('users.*')
                ->where(function ($query) use ($request) {
                    $query->where('users.name', 'like', '%' . $request->search . '%')
                        ->orWhere('users.last_name', 'like', '%' . $request->search . '%');
                })->limit(10)->get();

            foreach ($getUser as $value) {
                $type = '';
                if ($value->user_type == 1) {
                    $type = 'Admin';
                } elseif ($value->user_type == 2) {
                    $type = 'Teacher';
                } elseif ($value->user_type == 3) {
                    $type = 'Student';
                } elseif ($value->user_type == 4) {
                    $type = 'Parent';
                }

                $name = $value->name . ' ' . $value->last_name . ' - ' . $type;
                $json[] = ['id' => $value->id, 'text' => $name];
            }
        }

        echo json_encode($json);
    }

    public function SendEmailUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'user_id' => 'required',
            'message' => 'required',

        ]);

        if ($validator->passes()) {
            if (!empty($request->user_id)) {

                $user = User::find($request->user_id);
                $user->send_message = $request->message;
                $user->send_subject = $request->subject;

                Mail::to($user->email)->send(new SendEmailUserMail($user));
            }

            if (!empty($request->message_to)) {

                foreach ($request->message_to as $user_type) {

                    $getUser = User::select('users.*')
                        ->where('users.user_type', '=', $user_type)
                        ->where('users.is_delete', '=', 0)->get();

                    foreach ($getUser as $user) {

                        $user->send_message = $request->message;
                        $user->send_subject = $request->subject;

                        Mail::to($user->email)->send(new SendEmailUserMail($user));
                    }
                }
            }

            return redirect()->back()->with('success', 'Mail sent Successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    //Student Side
    public function MyNoticeBoard()
    {
        $data['getRecord'] = NoticeBoard::select('notice_board.*', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'notice_board.created_by')
            ->join('notice_board_message', 'notice_board_message.notice_board_id', '=', 'notice_board.id')
            ->where('notice_board_message.message_to', '=', Auth::user()->user_type)
            ->where('notice_board.publish_date', '<=', date('Y-m-d'))
            ->orderBy('notice_board.id', 'desc')->paginate(20);

        $data['header_title'] = 'My Notice Board';
        return view('student.mynoticeboard', $data);
    }

    //Teacher Side
    public function MyNoticeBoardTeacher()
    {
        $data['getRecord'] = NoticeBoard::select('notice_board.*', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'notice_board.created_by')
            ->join('notice_board_message', 'notice_board_message.notice_board_id', '=', 'notice_board.id')
            ->where('notice_board_message.message_to', '=', Auth::user()->user_type)
            ->where('notice_board.publish_date', '<=', date('Y-m-d'))
            ->orderBy('notice_board.id', 'desc')->paginate(20);

        $data['header_title'] = 'My Notice Board';
        return view('teacher.mynoticeboard', $data);
    }

    //Parent Side
    public function MyNoticeBoardParent()
    {
        $data['getRecord'] = NoticeBoard::select('notice_board.*', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'notice_board.created_by')
            ->join('notice_board_message', 'notice_board_message.notice_board_id', '=', 'notice_board.id')
            ->where('notice_board_message.message_to', '=', Auth::user()->user_type)
            ->where('notice_board.publish_date', '<=', date('Y-m-d'))
            ->orderBy('notice_board.id', 'desc')->paginate(20);

        $data['header_title'] = 'My Notice Board';
        return view('parent.mynoticeboard', $data);
    }
}
