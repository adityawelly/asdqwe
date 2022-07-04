<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DepartmentExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Department::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode Departemen',
            'Nama Departemen',
            'Deskripsi Departemen',
            'Dibuat pada',
            'Diupdate pada'
        ];
    }
}
