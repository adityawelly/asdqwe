<?php

namespace App\Exports;

use App\Models\LevelTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LevelTitleExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return LevelTitle::all();
    }

    public function headings(): array
    {
        return [
            'Kode Level Title',
            'Nama Level Title',
            'Deskripsi Level Title',
        ];
    }

    public function map($level_title): array
    {
        return [
            $level_title->level_title_code,
            $level_title->level_title_name,
            $level_title->level_title_description,
        ];
    }
}
