<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MandaysReport extends Model
{
    use SoftDeletes;
    
    protected $table = "mandays_report";

    /**
    *   Return Reporter
    */
    public function reporter() {
        return $this->belongsTo('\App\Model\Employee', 'reported_by', 'id');
    }

    /**
    *   Return Reporter
    */
    public function employee() {
        return $this->belongsTo('\App\Model\Employee', 'employee_id', 'id');
    }

    // public function productionReport()
    // {
    // 	return $this->belongsTo('\App\Model\ProductionReport', 'production_report_id', 'id');
    // }

    /**
    *   Return Group
    */
    public function group() {
        return $this->belongsTo('\App\Model\Group', 'group_id', 'id')->withDefault([
            'id' => '',
            'name' => ''
        ]);
    }
}
