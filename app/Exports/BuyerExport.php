<?php

namespace App\Exports;

use App\Model\Buyer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BuyerExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'Name'
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Buyer::select('name')->get();
    }
}
