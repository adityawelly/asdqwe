<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Training;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TrainingImport implements ToModel, WithValidation, WithHeadingRow
{
    use Importable;

    public $counter = 0;

    public function model(array $row)
    {
        $training = Training::firstOrCreate([
            'name' => $row['training_name'],
            'vendor' => $row['vendor'],
            'start_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['from'])
        ],[
            'type' => $row['type'],
            'category' => $row['category'],
            'end_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['to']),
            'duration' => $row['duration'],
            'notes' => $row['notes']
        ]);

        $employee_nik = DB::table('employees')->where('registration_number', $row['nik'])->first();
        $training->employees()->detach($employee_nik->id);
        $training->employees()->attach($employee_nik->id);

        // DB::table('employees_trainings')->insert([
        //     'training_id' => $training->id,
        //     'employee_id' => Employee::where('registration_number', $row['nik'])->first()->id
        // ]);

        ++$this->counter;
    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                Rule::in(['Internal', 'External'])
            ],
            'category' => [
                'required',
                Rule::in(['Softskill', 'Technical'])
            ],
            'training_name' => 'required',
            'nik' => 'required|digits_between:5,20|exists:employees,registration_number',
            'vendor' => 'required',
            'from' => 'required',
            'to' => 'required',
            'duration' => 'required|numeric|between:0,99.99',
            'notes' => 'nullable',
        ];
    }

    private function _format_tanggal($date){
		$date = str_replace("/","-",$date);
		$exp = explode('-',$date);
		if(count($exp) == 3) {
			$date = $exp[2].'-'.$exp[1].'-'.$exp[0];
		}
		return $date;
	}
}
