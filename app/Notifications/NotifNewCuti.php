<?php

namespace App\Notifications;

use App\Mail\NewCuti;
use App\Models\EmployeeLeave;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifNewCuti extends Notification
{
    use Queueable;

    protected $employeeLeave;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(EmployeeLeave $employeeLeave)
    {
        $this->employeeLeave = $employeeLeave;
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
        return (new NewCuti($this->employeeLeave));
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
            'msg' => '<b>'.$this->employeeLeave->employee->fullname.'</b> mengajukan cuti <b>'.$this->employeeLeave->leave->leave_name.'</b> baru',
        ];
    }
}
