<?php

namespace App\Events;

use App\Message;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user, $friend, $conversation,$message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, User $friend, $conversation,Message $message)
    {
        $this->user = $user;
        $this->friend = $friend;
        $this->message = $message;
        $this->conversation = $conversation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('chat_room.' . $this->conversation_id);

        return new PresenceChannel('conversation.' . $this->conversation->id);
    }



    public function broadcastWith()
    {
        return [
            'text_message' => $this->message->text,
            'created_at' => \Carbon\Carbon::createFromDate($this->message->created_at)->diffForHumans()
        ];
    }
}
