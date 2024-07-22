<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Message;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable=[
        'recaiver_id',
        'sender_id'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getRecaiver()
    {
        if ($this->sender_id === auth()->id()) {
            return User::firstWhere('id',$this->recaiver_id);
        }else{
            return User::firstWhere('id',$this->sender_id);
        }
    }

    public function isLastMessageReadByUser() : bool
    {
        $user = auth()->user();
        $lastMessage = $this->messages()->latest()->first();
        if ($lastMessage) {
            return $lastMessage->read_at !== null && $lastMessage->sender_id == $user->id;
        }
    }

    public function unreadMessageCount() : int {
        return $unreadMessages = Message::where('conversation_id','=', $this->id)
            ->where('recaiver_id', auth()->user()->id)
            ->whereNull('read_at')->count();
    }
}
