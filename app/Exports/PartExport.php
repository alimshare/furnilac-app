<?php

namespace App\Exports;

use App\Model\Part;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PartExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'Item Code',
            'Part Number',
            'Part Name',
            'Price',
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Part::select('item_code','part_number','part_name', 'price')->get();
    }
}
