<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationPostNewCommentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $post_user;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($post_user, $data)
    {
        $this->post_user = $post_user;
        $this->data = $data; // has arrya ['comment' => ,'user' => ]

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel_new_notification.' . $this->post_user->id);
    }



    public function broadcastWith()
    {
        $notifications_unread_count = $this->post_user->unreadNotifications->count();


       return [

        "notifications_unread_count" => $notifications_unread_count,

       ];

    }
}
