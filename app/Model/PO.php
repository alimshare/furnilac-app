<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PO extends Model
{
    use SoftDeletes;
    
    protected $table = "po";
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'po_number';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'char';

    /**
    *   Return Employee choosen as a PIC for current PO
    */
    public function pic() {
        return $this->belongsTo('\App\Model\Employee', 'pic_id', 'id')->withDefault([
            'nik' => '',
            'name' => ''
        ]);
    }

    /**
    *   Return Buyer
    */
    public function buyer() {
        return $this->belongsTo('\App\Model\Buyer', 'buyer_id', 'id')->withDefault([
            'id' => '',
            'name' => ''
        ]);
    }


    /**
    *   Return Detail PO
    */
    public function detail() {
        return $this->hasMany('\App\Model\PODetail', 'po_number', 'po_number');
    }

    /**
    *   Return Production Report Progress
    */
    public function productionReport() {
        return $this->hasMany('\App\Model\ProductionReport', 'po_number', 'po_number');
    }

    /**
    *   Return Mandays Report
    */
    public function mandaysReport() {
        // return $this->hasManyThrough('\App\Model\MandaysReport', '\App\Model\ProductionReport', 'po_number', 'production_report_id', 'po_number', 'id');
        return $this->hasMany('\App\Model\MandaysReport', 'po_number', 'po_number');
    }

}
