<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class POPrice extends Model
{
    use SoftDeletes;
    
    protected $table = "po_price";

    public function item()
    {
    	return $this->belongsTo('\App\Model\Item','item_code','item_code');
    }
}
