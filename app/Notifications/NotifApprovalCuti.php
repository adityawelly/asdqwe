<?php

namespace App\Notifications;

use App\Mail\ApprovalCuti;
use App\Models\EmployeeLeave;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifApprovalCuti extends Notification
{
    use Queueable;

    protected $employeeLeave;
    protected $params;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(EmployeeLeave $employeeLeave, array $params)
    {
        $this->employeeLeave = $employeeLeave;
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
        return (new ApprovalCuti($this->employeeLeave, $this->params));
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

    public function toDatabase()
    {
        $status = $this->employeeLeave->status == 'apv' ? 'Disetujui':'Ditolak';

        return [
            'send_at' => now(),
            'msg' => 'Pengajuan <b>'.$this->employeeLeave->leave->leave_category.'</b> ID '.$this->employeeLeave->id.' anda telah '.$status
        ];
    }
}
