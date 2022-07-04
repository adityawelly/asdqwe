<?php

namespace App\Notifications;

use App\Mail\NewWfh;
use App\Models\EmployeeWfh;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifNewWfh extends Notification
{
    use Queueable;

    protected $employeeWfh;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(EmployeeWfh $employeeWfh)
    {
        $this->employeeWfh = $employeeWfh;
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
        return (new NewWfh($this->employeeWfh));
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
            'msg' => '<b>'.$this->employeeWfh->employee->fullname.'</b> mengajukan pengajuan bekerja dari rumah, menunggu approval anda',
        ];
    }
}
