<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
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

    public function onProgress()
    {    	
    	$parameter = [
    		'poNumber' 	 => $this->po_number,
    		'itemCode' 	 => $this->item_code
    	];

		// DB::enableQueryLog();
    	$result = 	DB::select("select sum(pr.qty_output) as output
				from po_detail pd inner join production_report pr on pr.part_number = pd.part_number and pd.po_number = pr.po_number
				where  pd.po_number = :poNumber and pd.item_code = :itemCode", $parameter );
  //   	 dd($result);

		// $query = DB::getQueryLog();
		// print_r($query);
		// dd('stop');

        return (count($result) > 0 && $result[0]->output > 0) ? true : false;
    }
}
