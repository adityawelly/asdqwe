<?php

namespace App\Notifications;

use App\Mail\ApprovalIsoman;
use App\Models\EmployeeIsoman;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifApprovalIsoman extends Notification
{
    use Queueable;

    protected $employeeIsoman;
    protected $params;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(EmployeeIsoman $employeeIsoman, array $params)
    {
        $this->employeeIsoman = $employeeIsoman;
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
        return (new ApprovalIsoman($this->employeeIsoman, $this->params));
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
        $status = $this->employeeIsoman->status == 'apv' ? 'Disetujui':'Ditolak';

        return [
            'send_at' => now(),
            'msg' => 'Pengajuan Izin Isolasi Mandiri anda dengan ID '.$this->employeeIsoman->id.' anda telah '.$status
        ];
    }
}
