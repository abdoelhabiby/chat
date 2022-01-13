<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCommentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($post_user,$comment)
    {
        $this->post_user = $post_user;
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('channelNewComment_'.$this->comment->post_id);
        // return new Channel('channelNewComment');
    }




    public function broadcastWith()
    {

        return [
        "user" => $this->comment->user,
        "comment" => $this->comment


        ];

    }




}
