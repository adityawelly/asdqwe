<?php

namespace App\Notifications;

use App\Mail\NewLembur;
use App\Models\EmployeeLembur;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifNewLembur extends Notification
{
    use Queueable;

    protected $employeeLembur;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(EmployeeLembur $employeeLembur)
    {
        $this->employeeLembur = $employeeLembur;
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
        return (new NewLembur($this->employeeLembur));
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
            'msg' => '<b>'.$this->employeeLembur->employee->fullname.'</b> mengajukan pengajuan kerja lembur, menunggu approval anda',
        ];
    }
}
