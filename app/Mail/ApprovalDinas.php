<?php

namespace App\Mail;

use App\Models\EmployeeDinas;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovalDinas extends Mailable
{
    use Queueable, SerializesModels;

    protected $employeeDinas;
    protected $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmployeeDinas $employeeDinas, array $params)
    {
        $this->employeeDinas = $employeeDinas;
        $this->params = (object) $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if (!$this->employeeDinas->id) {
            return $this->view('maileclipse::templates.approvalDinas')
                    ->with([
                        'id' => 'dummy',
                        'status' => 'dummy',
                        'atasan_name' => 'dummy',
                    ]);
        }

        $status = $this->employeeDinas->status == 'apv' ? 'Disetujui':'Ditolak';
        $employee = $this->employeeDinas->employee;
        //$leave = $this->employeeDinas->leave;

        return $this->view('maileclipse::templates.approvalDinas')
                    ->subject('Status Pengajuan Dinas Luar Anda denganI D '.$this->employeeDinas->id.' telah '.$status)
                    ->to($employee->user->email)
                    ->with([
                        'id' => $this->employeeDinas->id,
                        'status' => $status,
                        'atasan_name' => $this->params->atasan_name,
                    ]);
    }
}
