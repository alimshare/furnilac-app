<?php

namespace App\Exports;

use App\MandaysReport;
use Maatwebsite\Excel\Concerns\FromCollection;

class MandaysReportExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MandaysReport::all();
    }
}
