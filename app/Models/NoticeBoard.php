<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeBoard extends Model
{
    use HasFactory;

    protected $table = 'notice_board';

    public static function getTotalNotice($message_to)
    {
        return NoticeBoard::select('notice_board.*', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'notice_board.created_by')
            ->join('notice_board_message', 'notice_board_message.notice_board_id', '=', 'notice_board.id')
            ->where('notice_board_message.message_to', '=', $message_to)
            ->where('notice_board.publish_date', '<=', date('Y-m-d'))
            ->count();
    }
    public function getMessage()
    {
        return $this->hasMany(NoticeBoardMessage::class, "notice_board_id");
    }

    public function getMessageTo($notice_board_id, $message_to)
    {
        return NoticeBoardMessage::where('notice_board_id', '=', $notice_board_id)
            ->where('message_to', '=', $message_to)
            ->first();
    }
}
