<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeLeaveQuotaExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;
    
    public function query()
    {
        return DB::table('v_quota_index as a')
                ->join('employees as b', 'b.registration_number', '=', 'a.employee_no')->orderByDesc('b.registration_number');
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama',
            'Tgl Join',
            'Start Date',
            'End Date',
            'Kuota',
            'Kuota Terpakai',
            'Kuota Periode Sebelumnya',
            'Sisa'
        ];
    }

    public function map($row): array
    {
        return [
            $row->employee_no,
            $row->fullname,
            date('d-m-Y', strtotime($row->date_of_work)),
            date('d-m-Y', strtotime($row->start_date)),
            date('d-m-Y', strtotime($row->end_date)),
            $row->qty,
            $row->used == 0 ? '0':$row->used,
            $row->qty_before,
            $row->qty - $row->used + $row->qty_before
        ];
    }
}
