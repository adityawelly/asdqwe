<?php

namespace App\Notifications;

use App\Mail\NewIsoman;
use App\Models\EmployeeIsoman;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifNewIsoman extends Notification
{
    use Queueable;

    protected $employeeIsoman;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(EmployeeIsoman $employeeIsoman)
    {
        $this->employeeIsoman = $employeeIsoman;
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
        return (new NewIsoman($this->employeeIsoman));
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
            'msg' => '<b>'.$this->employeeIsoman->employee->fullname.'</b> mengajukan pengajuan izin Isolasi Mandiri, menunggu approval anda',
        ];
    }
}
