<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApplyExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;
    protected $params;

    public function __construct(array $params) {
        $this->params = (object) $params;
    }

    public function collection()
    {
        $query = "select a.*, b.job_id, c.job_title_name from applier as a, jobs as b, job_titles as c where a.id_job = b.id and b.job_id = c.id";

        if ($this->params->start_date && $this->params->end_date) {
            $query .= " and a.insert_date between '{$this->params->start_date}' and '{$this->params->end_date}'";
        }

        if ($this->params->job_id != 'all') {
            $query .= " and b.job_id = {$this->params->job_id}";
        }
		
		if ($this->params->status_data != 'all') {
            $query .= " and a.status_data = '{$this->params->status_data}'";
        }

        return collect(DB::select($query));
    }

     function headings(): array
    {
        return [
			'Posisi Yang Dilamar',
			'Nama',
            'Email',
			'No Telp',
			'Alamat Lengkap',
			'NPWP',
            'Tempat, Tanggal Lahir',
            'Jenis Kelamin',
			'Status Perkawinan',
            'SIM',
            'Agama',
			'Pendidikan Terakhir',
			'Nama Institusi',
            'Jurusan',
            'Tahun Lulus',
            'Pengalaman Kerja Terakhir',
            'Jabatan',
			'Masa Kerja',
            'Alasan Berhenti',
            'Pencapaian Di tempat kerja',
			'Gaji Yang Diharapkan',
            'Waktu Join'
        ];
    }

    function map($row): array
    {
        return [
            $row->job_title_name,
			$row->fullname,
            $row->email,
			$row->phone,
            $row->address,
			$row->npwp ?? 'Tidak Diisi',
            $row->place .'-'. date('d-m-Y', strtotime($row->dob)),
            $row->gender == 'M' ?  'Laki - Laki' : 'Perempuan',
			$row->martial,
            $row->sim == 'AC' ? 'A & C' : $row->sim,
            $row->religion,
            $row->lastedu,
			$row->eduname,
			$row->edufocus,
            $row->yearedu,
			$row->companyname1,
			$row->lastpostion1,
            date('d-m-Y', strtotime($row->sdate1)) .'S/D'. date('d-m-Y', strtotime($row->edate1)),
			$row->reason1,
            $row->prestasi,
			$row->salary, 
			$row->workstart,				
        ];
    }
}