<?php

namespace App\Mail;

use App\Models\EmployeeLembur;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewLembur extends Mailable
{
    use Queueable, SerializesModels;

    protected $employeeLembur;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmployeeLembur $employeeLembur)
    {
        $this->employeeLembur = $employeeLembur;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (!$this->employeeLembur->id) {
            return $this->view('maileclipse::templates.newLembur')
                        ->with([
                            'requestor_name' => 'dummy',
                            'start_date' => 'dummy',
                            'end_date' => 'dummy',
                        ]);
        }

        $employee = $this->employeeLembur->employee;
        $atasan = $employee->superior;
        //$leave = $this->employeeLembur->leave;

        return $this->view('maileclipse::templates.newLembur')
                    ->subject('Pengajuan Izin Kerja Lembur oleh '.$employee->fullname.' menunggu approval')
                    ->to($atasan->user->email)
                    ->with([
                      
                        'requestor_name' => $employee->fullname,
                        'start_date' => $this->employeeLembur->start_date->format('d-m-Y'),
                        'end_date' => $this->employeeLembur->end_date->format('d-m-Y'),
                    ]);
    }
}
