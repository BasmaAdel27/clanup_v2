<?php

namespace App\Models;

use App\Notifications\Mesages\sendMessage;
use App\Services\Notification\Notification;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable=['message','user_id','group_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function sendMessage()
    {
        try {
            $members = $this->group->allmembers;
            Notification::send($members, new sendMessage($this));
        } catch (\Throwable $th) {}
    }
}
