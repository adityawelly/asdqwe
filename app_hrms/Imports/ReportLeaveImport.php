<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmployeeLeave;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ReportLeaveImport implements ToModel, WithValidation, WithHeadingRow
{
    use Importable;

    public $counter = 0;

    public function model(array $row)
    {
        EmployeeLeave::create([
            'start_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_mulai']),
            'end_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_akhir']),
            'reason' => $row['alasan'],
            'leave_id' => strtolower($row['kategori']) == 'cuti tahunan' ? null:$row['kategori'],
            'employee_registration_number' => $row['nik'],
            'status' => 'approved',
        ]);

        $this->counter++;
    }

    public function rules(): array
    {
        return [
            'nik' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
            'kategori' => 'required',
            'alasan' => 'required',
        ];
    }
}
