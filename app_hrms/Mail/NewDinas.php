<?php

namespace App\Mail;

use App\Models\EmployeeDinas;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewDinas extends Mailable
{
    use Queueable, SerializesModels;

    protected $employeeDinas;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmployeeDinas $employeeDinas)
    {
        $this->employeeDinas = $employeeDinas;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (!$this->employeeDinas->id) {
            return $this->view('maileclipse::templates.newDinas')
                        ->with([
                            'requestor_name' => 'dummy',
                            'start_date' => 'dummy',
                            'end_date' => 'dummy',
                            'total_days' => 'dummy',
                        ]);
        }

        $employee = $this->employeeDinas->employee;
        $atasan = $employee->superior;
        //$leave = $this->employeeDinas->leave;

        return $this->view('maileclipse::templates.newDinas')
                    ->subject('Pengajuan Izin Dinas Luar oleh '.$employee->fullname.' menunggu approval')
                    ->to($atasan->user->email)
                    ->with([
                      
                        'requestor_name' => $employee->fullname,
                        'start_date' => $this->employeeDinas->start_date->format('d-m-Y'),
                        'end_date' => $this->employeeDinas->end_date->format('d-m-Y'),
                        'total_days' => $this->employeeDinas->total,
                    ]);
    }
}
