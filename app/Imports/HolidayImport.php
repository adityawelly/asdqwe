<?php

namespace App\Imports;

use App\Models\Holiday;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class HolidayImport implements ToModel, WithValidation, WithHeadingRow
{
    use Importable;

    public $counter = 0;

    public function model(array $row)
    {
        Holiday::create([
            'date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal']),
            'date_desc' => $row['keterangan_libur'],
            'is_mass_leave' => strtolower($row['cuti_bersama']) == 'ya' ? true:false,
            'hk' => strtolower($row['hk']) == 'semua' ? 0:$row['hk']
        ]);

        $this->counter++;
    }

    public function rules(): array
    {
        return [
            'tanggal' => 'required',
            'keterangan_libur' => 'required',
            'cuti_bersama' => 'required|in:Ya,Tidak',
            'hk' => 'required',
        ];
    }
}
