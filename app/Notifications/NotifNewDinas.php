<?php

namespace App\Notifications;

use App\Mail\NewDinas;
use App\Models\EmployeeDinas;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifNewDinas extends Notification
{
    use Queueable;

    protected $employeeDinas;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(EmployeeDinas $employeeDinas)
    {
        $this->employeeDinas = $employeeDinas;
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
        return (new NewDinas($this->employeeDinas));
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
            'msg' => '<b>'.$this->employeeDinas->employee->fullname.'</b> mengajukan pengajuan izin dinas luar, menunggu approval anda',
        ];
    }
}
