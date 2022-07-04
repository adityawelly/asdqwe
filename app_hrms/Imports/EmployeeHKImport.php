<?php

namespace App\Imports;

use App\Models\EmployeeHK;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeeHKImport implements ToModel, WithValidation, WithHeadingRow
{
    use Importable;

    public $counter = 0;

    public function model(array $row)
    {
        EmployeeHK::updateOrCreate([
            'employee_no' => $row['nik']
        ],[
            'hk' => $row['hari_kerja']
        ]);

        $this->counter++;
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|digits_between:5,20',//|exists:employees,registration_number',
            'hari_kerja' => 'required|numeric',
        ];
    }
}
