<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionReport extends Model
{
    use SoftDeletes;
    
    protected $table = "production_report";
    
    /**
    *   Return Reporter
    */
    public function reporter() {
        return $this->belongsTo('\App\Model\Employee', 'reported_by', 'id');
    }

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
