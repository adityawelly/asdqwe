<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JobExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;
    protected $params;

    public function __construct(array $params) {
        $this->params = (object) $params;
    }

    public function collection()
    {
        $query = "select a.id, a.status,  a.created_at, a.views, a.wawancara, a.tidak_sesuai, a.terpilih, b.job_title_name, c.grade_title_name, d.department_name, e.region_city from jobs as a,
				  job_titles as b, grade_titles as c, departments as d, company_regions as e where a.job_id = b.id and a.level_id = c.id
				  and a.dept_id = d.id and a.region_id = e.id";

        if ($this->params->start_date && $this->params->end_date) {
            $query .= " and a.created_at between '{$this->params->start_date}' and '{$this->params->end_date}'";
        }

        if ($this->params->status != 'all') {
            $query .= " and a.status = {$this->params->status}";
        }

        return collect(DB::select($query));
    }

    function headings(): array
    {
        return [
            'JOB ID',
			'TANGGAL ENTRY',
            'ESTIMATED CLOSING DATE',
            'JOB NAME',
            'REGION',
            'BELUM DI PROSES',
            'WAWANCARA',
            'TERPILIH',
            'TIDAK SESUAI'
        ];
    }

    function map($row): array
    {
        return [
            $row->id,
            date('d-m-Y', strtotime($row->created_at)),
            date('d-m-Y', strtotime($row->created_at. ' + 2 months')),
            $row->job_title_name,
            $row->region_city ?? '-',
            $row->views ?? '-',
            $row->wawancara ?? '-',
            $row->terpilih ?? '-',
            $row->tidak_sesuai ?? '-',
        ];
    }
}
