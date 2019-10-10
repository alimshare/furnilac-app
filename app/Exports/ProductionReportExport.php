<?php

namespace App\Exports;

use App\Model\ProductionReport;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ProductionReportExport implements FromQuery
{
    use Exportable;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function query()
    {
        return ProductionReport::query()->whereBetween('reported_date', [$this->startDate, $this->endDate]);
    }
}
