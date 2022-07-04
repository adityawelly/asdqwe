<?php

namespace App\Notifications;

use App\Mail\ApprovalDinas;
use App\Models\EmployeeDinas;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifApprovalDinas extends Notification
{
    use Queueable;

    protected $employeeDinas;
    protected $params;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(EmployeeDinas $employeeDinas, array $params)
    {
        $this->employeeDinas = $employeeDinas;
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
        return (new ApprovalDinas($this->employeeDinas, $this->params));
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
        $status = $this->employeeDinas->status == 'apv' ? 'Disetujui':'Ditolak';

        return [
            'send_at' => now(),
            'msg' => 'Pengajuan Izin Dinas Luar dengan ID '.$this->employeeDinas->id.' anda telah '.$status
        ];
    }
}
