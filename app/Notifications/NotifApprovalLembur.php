<?php

namespace App\Notifications;

use App\Mail\ApprovalLembur;
use App\Models\EmployeeLembur;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifApprovalLembur extends Notification
{
    use Queueable;

    protected $employeeLembur;
    protected $params;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(EmployeeLembur $employeeLembur, array $params)
    {
        $this->employeeLembur = $employeeLembur;
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
        return (new ApprovalLembur($this->employeeLembur, $this->params));
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
        $status = $this->employeeLembur->status == 'apv' ? 'Disetujui':'Ditolak';

        return [
            'send_at' => now(),
            'msg' => 'Pengajuan Kerja Lembur Anda dengan ID '.$this->employeeLembur->id.' anda telah '.$status
        ];
    }
}
