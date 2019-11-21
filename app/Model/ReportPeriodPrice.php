<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReportPeriodPrice extends Model
{    
    protected $table = "report_period_price";

    public function item(){
        return $this->belongsTo('\App\Model\Item', 'item_id', 'id');
    }

    public function part(){
        return $this->belongsTo('\App\Model\Part', 'part_id', 'id');
    }
}
