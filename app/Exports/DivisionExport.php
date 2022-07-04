<?php

namespace App\Exports;

use App\Models\Division;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DivisionExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Division::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode Divisi',
            'Nama Divisi',
            'Deskripsi Divisi',
            'Dibuat pada',
            'Diupdate pada'
        ];
    }
}
