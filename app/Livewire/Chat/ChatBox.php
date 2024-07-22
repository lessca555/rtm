<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Livewire\Component;
use App\Livewire\Chat\ChatList;
use App\Notifications\MessageSent;

class ChatBox extends Component
{
    public $selectedConversation;
    public $body;
    public $loadedMessages;
    public $paginate_var=10;

    protected $listener=[
        'loadMore'
    ];

    public function getListeners()
    {
        $auth_id = auth()->user()->id;

        return [
            'loadMore',
            "echo-private:users.{$auth_id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated"=>'broadcastedNotifications'
        ];
    }

    public function broadcastedNotifications($event)
    {
        // dd($event);
        if($event['type']== MessageSent::class){
            if($event['conversation_id']==$this->selectedConversation->id){
                $this->dispatch('scroll-bottom');

                $newMessage = Message::find($event['message_id']);

                //push message
                $this->loadedMessages->push($newMessage);
            }
        }
    }

    public function loadMore(): void
    {
        // dd('detected');
        $this->paginate_var += 10;
        $this->loadMessages();
        $this->dispatch('update-chat-height');
    }

    public function loadMessages()
    {
        $count = Message::where('conversation_id', $this->selectedConversation->id)->count();
        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)
        ->skip($count - $this->paginate_var)
        ->take($this->paginate_var)
        ->get();
    }

    public function sendMessage()
    {
        // dd($this->body);
        $this->validate([
            'body' => 'required|string'
        ]);

        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'recaiver_id' => $this->selectedConversation->getRecaiver()->id,
            'body' => $this->body,
        ]);

        $this->reset('body');

        //scroll to bottom
        $this->dispatch('scroll-bottom');

        // dd($createdMessage);

        $this->loadedMessages->push($createdMessage);

        //update conversation model
        $this->selectedConversation->updated_at = now();
        $this->selectedConversation->save();

        //refresh chatlist
        $this->dispatch('refresh')->to(ChatList::class);
        // dd($refs);

        //broadcast
        $this->selectedConversation->getRecaiver()
            ->notify(new MessageSent(
                Auth()->User(),
                $createdMessage,
                $this->selectedConversation,
                $this->selectedConversation->getRecaiver()->id
            ) );

    }

    public function mount()
    {
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
