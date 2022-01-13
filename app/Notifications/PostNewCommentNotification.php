<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostNewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data; // has arrya ['comment' => ,'user' => ]
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }






    public function toDatabase()
    {
        $data = $this->data;

        return [
            "url" => 'link notifiction to go [post_id = '. $data['comment']->post_id .']',
            "notifiction_text" => "mr: " . $this->data['user']->name . " add comment to your post",
            "image" => 'image how is added the comment'


        ];



    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // return $this->data;
    }


}
