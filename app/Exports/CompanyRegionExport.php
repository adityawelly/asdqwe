<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\CompanyRegion;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CompanyRegionExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return CompanyRegion::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kota',
            'Alamat Regional',
            'Dibuat pada',
            'Diupdate pada'
        ];
    }
}
