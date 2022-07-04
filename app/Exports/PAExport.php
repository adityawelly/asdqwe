<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PAExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;
    protected $params;

    public function __construct(array $params) {
        $this->params = (object) $params;
    }

    public function collection()
    {
        $query = "select a.*, a.tahun as tahunan, b.*, c.job_title_name, d.department_name, e.division_name, f.grade_title_name, g.region_city , h.fullname as nama_atasan from 
				  employee_pa as a, employees as b, job_titles as c, departments as d, divisions as e, grade_titles as f, company_regions as g, employees as h 
				  where a.employee_id=b.id and b.job_title_id = c.id and b.department_id = d.id and b.division_id = e.id and b.grade_title_id = f.id
				  and b.company_region_id = g.id and b.direct_superior = h.id";

		
        if ($this->params->tahun) {
            $query .= " and a.tahun = '{$this->params->tahun}' order by b.registration_number asc";
        }
		
		/*
        if ($this->params->status != 'all') {
            $query .= " and a.Flag_proses = {$this->params->status}";
        }

        if ($this->params->is_approved_all) {
            $query .= " and a.ApprovedAll = 1";
        }
		*/

        return collect(DB::select($query));
    }

     function headings(): array
    {
        return [
			'NIK',
            'TGL Masuk',
			'Nama',
			'Jabatan',
            'Divisi',
            'Dept',
			'Grade Title',
            'Grade',
            'Level',
			'Lokasi',
            'Status',
			'Tahun Penilaian',
            'Atasan Langsung',
            'Nilai PA Sem 1',
            'Nilai KPI Sem 1',
			'Nilai Akhir Sem 1',
			'Score Sem 1',
            'Nilai PA Sem 2',
            'Nilai KPI Sem 2',
			'Nilai Akhir Sem 2',
			'Score Sem 2',
			'Nilai Akhir'
        ];
    }

    function map($row): array
    {
        return [
            $row->registration_number,
			date('d-m-Y', strtotime($row->date_of_work)),
            $row->fullname,
            $row->job_title_name,
			$row->division_name,
            $row->department_name,
			$row->grade_title_name,
            $row->grade,
            $row->level,
            $row->region_city,
			$row->status,
			$row->tahunan,
            $row->nama_atasan,
			$row->pa_sem1 ?? 0,
			$row->kpi_sem1 ?? 0,
            $row->tot_sem1 ?? 0,
			$row->grd_sem1,
			$row->pa_sem2 ?? 0,
			$row->kpi_sem2 ?? 0,
            $row->tot_sem2 ?? 0,
			$row->grd_sem2,   
			($row->tot_sem1 + $row->tot_sem2)/2,
        ];
    }
}