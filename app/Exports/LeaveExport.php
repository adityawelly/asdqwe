<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Leave;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LeaveExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Leave::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Kategori Cuti',
            'Kuota Per Tahun',
            'Deskripsi',
            'Dibuat pada',
            'Diupdate pada'
        ];
    }
}
