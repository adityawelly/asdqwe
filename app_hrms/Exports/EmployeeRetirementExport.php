<?php

namespace App\Exports;

use App\Models\EmployeeRetirement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeRetirementExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return EmployeeRetirement::with('employee')->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama Karyawan',
            'Tanggal Resign',
            'Alasan',
            'Keterangan',
        ];
    }

    public function map($resign): array
    {
        return [
            $resign->employee->registration_number,
            $resign->employee->fullname,
            $resign->date_of_retirement,
            $resign->reason,
            $resign->note,
        ];
    }
}
