<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Conversation;

class Message extends Model
{
    use HasFactory;

    protected $fillable=[
        'body',
        'sender_id',
        'recaiver_id',
        'conversation_id',
        'read_at',
        'recaiver_deleted_at',
        'sender_deleted_at',
    ];

    protected $dates=[
        'read_at',
        'recaiver_deleted_at',
        'sender_deleted_at',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function isRead():bool
    {
        return $this->read_at != null;
    }

}
