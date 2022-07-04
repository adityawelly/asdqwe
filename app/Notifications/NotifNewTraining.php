<?php

namespace App\Notifications;

use App\Mail\NewTraining;
use App\Models\TrainingSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifNewTraining extends Notification
{
    use Queueable;

    protected $trainingSubmission;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrainingSubmission $trainingSubmission)
    {
        $this->trainingSubmission = $trainingSubmission;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new NewTraining($this->trainingSubmission));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'send_at' => now(),
            'msg' => 'Pengajuan training baru ID <b>'.$this->trainingSubmission->id.'</b> oleh <b>'.$this->trainingSubmission->submitted_by->fullname.'</b>',
        ];
    }
}
