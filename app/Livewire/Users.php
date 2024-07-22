<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\User;
use Livewire\Component;

class Users extends Component
{
    public function message($userId)
    {
        // dd($userId);
        //id user yang sedang login
        $authUserId = auth()->id();

        //mengecek apakah pernah ada chat
        $existConversation = Conversation::where(function($query) use($authUserId, $userId){
            $query->where('sender_id', $authUserId)->where('recaiver_id', $userId);
        })->orWhere( function($query) use ($authUserId, $userId){
            $query->where('sender_id', $userId)->where('recaiver_id', $authUserId);
        } )->first();

        if($existConversation){
            return redirect()->route('chat', ['query' => $existConversation->id]);
        }

        //buat room chat baru
        $createChat = Conversation::create([
            'sender_id' => $authUserId,
            'recaiver_id' => $userId
        ]);

        return redirect()->route('chat', ['query' => $createChat->id]);


    }

    public function render()
    {
        return view('livewire.users', ['users' => User::where('id', '!=', auth()->id())->get()]);
    }
}
