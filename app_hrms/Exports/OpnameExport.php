<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OpnameExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;

    public function query()
    {
        return DB::table('employee_opname_quotas as a')
                    ->select(['a.*', 'b.fullname'])
                    ->join('employees as b', 'b.registration_number', '=', 'a.employee_no')
                    ->orderBy('a.employee_no');
    }

    function headings(): array
    {
        return [
            'NIK',
            'Nama',
            'Start Date',
            'End Date',
            'Qty',
            'Status',
            'Note',
            'Dibuat Pada'
        ];
    }

    function map($row): array
    {
        return [
            $row->employee_no,
            $row->fullname,
            $row->start_date,
            $row->end_date,
            $row->qty,
            $row->status,
            $row->note,
            $row->created_at
        ];
    }
}
