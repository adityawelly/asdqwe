<?php

namespace App\Notifications;

use App\Mail\ApprovalTraining;
use App\Models\TrainingSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifApprovalTraining extends Notification
{
    use Queueable;

    protected $trainingSubmission;
    protected $params;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrainingSubmission $trainingSubmission, array $params)
    {
        $this->trainingSubmission = $trainingSubmission;
        $this->params = $params;
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
        return (new ApprovalTraining($this->trainingSubmission, $this->params));
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
            'msg' => 'Pengajuan Training anda ID <b>'.$this->trainingSubmission->id.'</b> telah di<b>'.$this->params['status'].'</b>',
        ];
    }
}
