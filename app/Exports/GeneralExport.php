<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GeneralExport implements FromCollection, WithHeadings
{

    public function __construct($heading = array(), $data = array())
    {
        $this->heading  = $heading;
        $this->data     = $data;
    }

    public function headings(): array
    {
        return $this->heading;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data);
    }
}
