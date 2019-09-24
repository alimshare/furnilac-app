<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Item;
use App\Model\Part;

class ApiController extends Controller
{
    public function itemInfo($itemCode)
    {
    	$obj = Item::where('item_code', $itemCode)->select('item_code','item_name','factory_style','buyer_style')->first();
    	return response()->json($obj);
    }

    public function partList($poNumber = '')
    {
    	$po = \App\Model\PO::where('po_number', $poNumber)->first();
    	if ($po == null) return response()->json(array());

    	$partList = $po->detail()->select('part_number', 'unit_qty', 'price', 'qty')->get();
    	return response()->json($partList);
    }

    public function partInfo($poNumber='', $partNumber='') {
        $poDetail = \App\Model\PODetail::where('po_number', $poNumber)->where('part_number', $partNumber)
            ->select('po_number','part_number','unit_qty','price','qty')->first();
        
        $poDetail->total_order = $poDetail->unit_qty * $poDetail->qty;
        $poDetail->production_output = $poDetail->getProductionOutput();
        
        return response()->json($poDetail);
    }

}
