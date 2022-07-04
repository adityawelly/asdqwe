<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EmployeeResignExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;

     public function query()
    {
        return DB::table('v_quota_resign')
                ->orderByDesc('fullname');
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama',
            'Start Date',
            'End Date',
            'Kuota',
            'Kuota Terpakai',
            'Kuota Sebelumnya',
            'Sisa Kuota',
        ];
    }

    public function map($row): array
    {
        return [
            $row->employee_no,
            $row->fullname,
            date('d-m-Y', strtotime($row->start_date)),
            date('d-m-Y', strtotime($row->end_date)),
            $row->qty,
            $row->used,
            $row->qty_before,
            $row->sisa,
        ];
    }
}
