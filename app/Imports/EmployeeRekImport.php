<?php

namespace App\Imports;

use App\Models\EmployeeSalary;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeeRekImport implements ToModel, WithValidation, WithHeadingRow
{
    use Importable;

    public $counter = 0;

    public function model(array $row)
    {
		$id = Employee::where('registration_number', $row['nik'])->first()->id;
		
        EmployeeSalary::updateOrCreate([
            'employee_id' => $id
			],[
            'bank' => $row['bank'],
			'bank_account_number' => $row['no_rekening']
			]);

        $this->counter++;
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|digits_between:5,20',//|exists:employees,registration_number',
            'no_rekening' => 'required|numeric',
        ];
    }
}
