<?php

namespace App\Exports;

use App\Models\Training;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class TrainingExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Training::with('employees')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tipe',
            'Category',
            'Nama Training',
            'Vendor',
            'Mulai',
            'Berakhir',
            'Durasi (Jam)',
            'Dibuat pada',
            'Peserta'
        ];
    }

    function map($row): array
    {
        return [
            $row->id,
            $row->type,
            $row->category,
            $row->name,
            $row->vendor,
            $row->start_date,
            $row->end_date,
            $row->duration,
            date('d-m-Y', strtotime($row->created_at)),
            $row->employees->map(function ($item, $key){
                $item->fullname = $item->registration_number.'-'.$item->fullname;
                return $item;
            })->implode('fullname', PHP_EOL)
        ];
    }

    function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = 'J1:J'.$event->sheet->getDelegate()->getHighestDataRow();
                $event->sheet->getDelegate()->getColumnDimension('J')->setAutoSize(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true)->setVertical('top');
            }
        ];
    }
}
