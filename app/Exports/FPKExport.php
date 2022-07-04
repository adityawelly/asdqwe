<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FPKExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;
    protected $params;

    public function __construct(array $params) {
        $this->params = (object) $params;
    }

    public function collection()
    {
        $query = "select a.*, j.fullname as nama_atasan_baru,
				b.employee_id, b.fullname , b.date_of_birth, b.date_of_work, b.religion, b.last_education, b.education_focus, 
				b.department_name, d.registration_number, d.fullname as nama_creator , c.* , e.job_title_name , 
				f.job_title_name as jab_baru, g.department_name , h.department_name as dept_baru, i.region_city, k.level_title_name, l.level_title_name as level_baru, 
				m.division_name as divisi_lama, n.division_name as divisi_baru from 
				f_job_newer as a, employeemaster as  b, f_job_newer_perihal as  c, employees as d, job_titles as e, 
				job_titles as f , departments as g, departments as h, company_regions as i, employees as j, level_titles as k, level_titles as l, divisions as m, divisions as n
				where a.Jab_lama=e.job_title_code and a.Jab_baru=f.job_title_code and a.Insert_user = d.id and 
				a.ReqId=c.Seq_id and a.Nik=b.id and a.Dept_lama = g.department_code and a.Dept_baru = h.department_code and a.Lokasi_baru = i.id 
				and a.Atasan_baru=j.id and a.Level_lama = k.id and b.Level = l.id and g.division_id = m.id and h.division_id = n.id";

        if ($this->params->start_date && $this->params->end_date) {
            $query .= " and a.Insert_date between '{$this->params->start_date}' and '{$this->params->end_date}'";
        }

        if ($this->params->status != 'all') {
            $query .= " and a.Flag_proses = {$this->params->status}";
        }

        if ($this->params->is_approved_all) {
            $query .= " and a.ApprovedAll = 1";
        }

        return collect(DB::select($query));
    }

     function headings(): array
    {
        return [
			'NIK',
            'No FPK',
			'No SK',
			'No PKWT / PKWTT',
            'Tanggal Masuk Kerja',
            'Nama Karyawan',
			'Divisi Lama',
            'Dept. Lama',
            'Atasan Langsung Lama',
			'GT. Lama',
            'JT. Lama',
            'GR. L',
            'LV. L',
            'Lok. L',
			'Divisi Baru',
            'Dept Baru',
            'Atasan Langsung Baru',
			'GT. Baru',
            'JT. Baru',
            'GR. B',
            'LV. B',
            'Lok. B',
			'Effektif Date SK / PKWT',
			'Jenis FPK',
			'SK'
        ];
    }

    function map($row): array
    {
        return [
            $row->employee_id,
			$row->fpk_no,
            $row->sk_no ?? '-',
            '-',
            date('d-m-Y', strtotime($row->date_of_work)),
            $row->fullname,
			$row->divisi_lama,
            $row->department_name,
            $row->nama_creator,
			$row->level_title_name,
            $row->job_title_name,
            $row->Kelas_lama,
            $row->Level_lama,
			$row->Lokasi_lama,
			$row->divisi_baru,
            $row->dept_baru,
			$row->nama_atasan_baru,
			$row->level_baru,
            $row->jab_baru,
			$row->Kelas_baru,
            $row->Level,
			$row->region_city,   
			date('d-m-Y', strtotime($row->Eff_date)),
			$row->perpanjangan_kontrak == 1 ? 'Perpanjangan Kontrak Kerja'  : '',
			!empty($row->sk_no) ? 'Done' : '',
        ];
    }
}