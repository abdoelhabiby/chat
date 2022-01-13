<?php

namespace App\Listeners;

use App\Events\NewCommentEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\NotificationPostNewCommentEvent;
use App\Notifications\PostNewCommentNotification;

class NewCommentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NewCommentEvent $event)
    {
        $post_user = $event->post_user;

        $content = [
            'comment' => $event->comment,
            'user' => $event->comment->user,
        ];


        $post_user->notify(new PostNewCommentNotification($content));

        event(new NotificationPostNewCommentEvent($post_user,$content));


    }
}
