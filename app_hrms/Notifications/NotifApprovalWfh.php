<?php

namespace App\Notifications;

use App\Mail\ApprovalWfh;
use App\Models\EmployeeWfh;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifApprovalWfh extends Notification
{
    use Queueable;

    protected $employeeWfh;
    protected $params;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(EmployeeWfh $employeeWfh, array $params)
    {
        $this->employeeWfh = $employeeWfh;
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
        return (new ApprovalWfh($this->employeeWfh, $this->params));
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
        $status = $this->employeeWfh->status == 'apv' ? 'Disetujui':'Ditolak';

        return [
            'send_at' => now(),
            'msg' => 'Pengajuan Izin Bekerja Dari Rumah dengan ID '.$this->employeeWfh->id.' anda telah '.$status
        ];
    }
}
