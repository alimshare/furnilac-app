<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PODetail extends Model
{
    use SoftDeletes;
    
    protected $table = "po_detail";

    public function getProductionOutput()
    {
    	$qty = 	DB::table('production_report')
                ->select(DB::raw('po_number, part_number, sum(qty_output) as qty'))
                ->where('po_number', $this->po_number)
                ->where('part_number', $this->part_number)
                ->groupBy('po_number', 'part_number')->pluck('qty')
                ->first();

        return ($qty == null) ? 0 : $qty;
    }
}
