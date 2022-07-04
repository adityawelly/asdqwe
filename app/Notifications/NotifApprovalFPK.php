<?php

namespace App\Notifications;

use App\Mail\ApprovalFPK;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifApprovalFPK extends Notification
{
    use Queueable;

    protected $params;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $params)
    {
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
        return (new ApprovalFPK($this->params));
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
            'msg' => '[HRMS] Pengajuan FPK ID <b>'.$this->params['ReqNo'].'</b> telah di <b>'.($this->params['ApprovalSts'] == 1 ? 'APPROVE':'CANCEL').'</b>',
        ];
    }
}
