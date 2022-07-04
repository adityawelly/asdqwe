<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class LeaveQuotaImport implements ToModel, WithValidation, WithHeadingRow
{
    use Importable;

    public $counter = 0;

    public $data = [];

    public function model(array $row)
    {
        DB::table('employee_leave_quotas')->updateOrInsert([
            'employee_no' => $row['nik'],
            'start_date' => Carbon::instance(Date::excelToDateTimeObject($row['start_date'])),
            'end_date' => Carbon::instance(Date::excelToDateTimeObject($row['end_date'])),
        ],[
            'qty' => $row['kuota'],
            'used' => $row['kuota_terpakai'],
            'qty_before' => $row['kuota_periode_sebelumnya'],
            'created_at' => now(),
        ]);

        $this->counter++;
    }

    public function rules(): array
    {
        return [
            'nik' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'kuota' => 'required|numeric',
            'kuota_terpakai' => 'required|numeric',
            'kuota_periode_sebelumnya' => 'required|numeric'
        ];
    }
}
