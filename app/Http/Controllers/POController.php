<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Model\PO;
use App\Model\PODetail;
use App\Model\Item;
use App\Model\Employee;
use App\Model\Buyer;
use App\Model\Part;

class POController extends Controller
{
    protected $BASE_PATH = '/po';
	protected $VIEW_PATH = 'modules.po.';
	public $data = array();

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }   
    
	public function list()
	{
		$list = PO::orderBy('transaction_date','asc')->get();
		return view($this->VIEW_PATH.'list')->with('list', $list);
	}
    
	public function new()
	{
		$this->data['item'] 	 = Item::all();
		$this->data['employees'] = Employee::all();
		$this->data['buyers'] 	 = Buyer::all();
		return view($this->VIEW_PATH.'form', $this->data);
	}

	/*
	*	Save PO and PO Detail using Transaction to implement Atomicity
	*/
	public function save(Request $request)
	{
		// dd($request->all());
		
		$obj = new PO;
		$obj->po_number 		= $request->input('poNumber');
		$obj->pic_id 			= $request->input('picId');
		$obj->buyer_id 			= $request->input('buyerId');
		$obj->transaction_date 	= $request->input('transactionDate');
		$obj->sw_begin 			= $request->input('startDate');
		$obj->sw_end 			= $request->input('endDate');
		$obj->notice_date		= $request->input('noticeDate');


		$itemList  = $request->input('itemCode');
		$qtyList   = $request->input('qty');
		$priceList = $request->input('price');
		
		$poPrices = array();
		$orderList = array();
		for ($i=0; $i < count($itemList); $i++) { 
			
			$itemCode  = $itemList[$i];
			$itemPrice = $priceList[$i];

			$poPrice['po_number'] 		= $obj->po_number;
			$poPrice['item_code'] 		= $itemCode;
			$poPrice['selling_price'] 	= $itemPrice;
			$poPrice['qty'] 			= $qtyList[$i];
			$poPrice['created_at'] 	= date('Y-m-d H:i:s');
			$poPrices[] = $poPrice;

			$partList = Part::where('item_code', $itemCode)->select('part_number','price','qty')->get();

			foreach ($partList as $key => $part) {				
				$detail = array();
				$detail['po_number'] 	= $obj->po_number;
				$detail['item_code']	= $itemCode;
				$detail['part_number'] 	= $part->part_number;
				$detail['unit_qty'] 	= $part->qty;
				$detail['price'] 		= $part->price;
				$detail['qty'] 			= $qtyList[$i];
				$detail['created_at']	= date('Y-m-d H:i:s');
				$orderList[] = $detail;
			}

		}

		// dd($poPrices);
		// dd($orderList);
		DB::beginTransaction();

		$isSuccessPo = $obj->save();
		$isSuccessPrices = DB::table('po_price')->insert($poPrices);
		$isSuccessDetail = DB::table('po_detail')->insert($orderList);

		if ($isSuccessPo && $isSuccessPrices && $isSuccessDetail) {
			DB::commit();
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'save new PO success !', 'type'=>'success']);
		} else {
			DB::rollBack();
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to save new PO !', 'type'=>'danger']);
		}

	}

	public function edit($poNumber = "")
	{
		if ($poNumber == "") return redirect($this->BASE_PATH);

		$poNumber = base64_decode($poNumber);
		$po 	  = PO::where('po_number', $poNumber)->first();
		if ($po == null) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'PO Number Not Found !', 'type'=>'danger']);
		}
		$this->data['po'] = $po;
		$this->data['item'] 	 = Item::all();
		$this->data['employees'] = Employee::all();
		$this->data['buyers'] 	 = Buyer::all();

		return view($this->VIEW_PATH.'form-edit', $this->data);

	}

	public function update(Request $req)
	{
		// dd($req->all());

		$poNumber 	= $req->input('poNumber');

		$po = \App\Model\PO::where('po_number', $poNumber)->first();
		if ($po == null) return redirect($this->BASE_PATH)->with('alert', ['message'=>'PO Number Not Found !', 'type'=>'danger']);

		$po->buyer_id 	 = $req->input('buyerId');
		$po->pic_id 	 = $req->input('picId');
		$po->sw_begin 	 = $req->input('startDate');
		$po->sw_end 	 = $req->input('endDate');
		$po->notice_date = $req->input('noticeDate');
		$po->transaction_date = $req->input('transactionDate');

		DB::beginTransaction();

		/* Existing Item */
		$itemList = $req->input('item');
		foreach ($itemList  as $itemCode => $data) {

			$temp = $po->prices()->where('item_code', $itemCode)->first();
			$currentPrice = str_replace(".", "", $data['price']);
			$currentQty = $data['qty'];

			if ($temp->selling_price != $currentPrice){
				$temp->selling_price = $currentPrice;
			}

			$isChangeQty = false;
			if ($temp->qty != $currentQty){
				$temp->qty = $currentQty;
				$isChangeQty = true;
			}

			if ($isChangeQty){
				$detailPO = $po->detail()->where('item_code', $itemCode)->get();
				foreach ($detailPO as $key => $detail) {
					$detail->qty = $currentQty;
					$detail->save(); // update qty in each part
				}
			}

			$temp->save(); // update existing po_price by item_code

		}

		/* New Item */
		$newItems 	= $req->input('newItem');
		$newQtys 	= $req->input('newQty');
		$newIPrices = $req->input('newPrice');

		$poPrices 	= array();
		$orderList 	= array();
		for ($i=0; $i < count($newItems); $i++) { 
			
			$itemCode  = $newItems[$i];
			if ($itemCode == null || $itemCode == "") continue;

			$itemPrice = $newIPrices[$i];

			$poPrice['po_number'] 		= $poNumber;
			$poPrice['item_code'] 		= $itemCode;
			$poPrice['selling_price'] 	= str_replace(".", "", $itemPrice);
			$poPrice['qty'] 			= $newQtys[$i];
			$poPrice['created_at'] 		= date('Y-m-d H:i:s');

			if (!$po->itemExists($itemCode)) {
				$poPrices[] = $poPrice;
			}

			$partList = Part::where('item_code', $itemCode)->select('part_number','price','qty')->get();

			foreach ($partList as $key => $part) {				
				$detail = array();
				$detail['po_number'] 	= $poNumber;
				$detail['item_code'] 	= $itemCode;
				$detail['part_number'] 	= $part->part_number;
				$detail['unit_qty'] 	= $part->qty;
				$detail['price'] 		= $part->price;
				$detail['qty'] 			= $newQtys[$i];
				$detail['created_at']	= date('Y-m-d H:i:s');

				if (!$po->partExists($part->part_number)) {
					$orderList[] = $detail;					
				}
			}
		}

		// dd($poPrices);
		// dd($orderList);

		$isSuccessPo = $po->save();
		$isSuccessPrices = DB::table('po_price')->insert($poPrices);
		$isSuccessDetail = DB::table('po_detail')->insert($orderList);

		$redirectTo = $this->BASE_PATH.'/edit/'.base64_encode($poNumber);

		if ($isSuccessPo && $isSuccessPrices && $isSuccessDetail) {
			DB::commit();
			return redirect($redirectTo)->with('alert', ['message'=>'update PO success !', 'type'=>'success']);
		} else {
			DB::rollBack();
			return redirect($redirectTo)->with('alert', ['message'=>'<b>failed</b> to update PO !', 'type'=>'danger']);
		}
	}

	public function monitor($poNumber = "")
	{
		
		if ($poNumber == "") {
			$this->data['poNumber'] = $poNumber;
			return view($this->VIEW_PATH.'po-monitor', $this->data);
		}
		
		$poNumber = base64_decode($poNumber);
		$this->data['poNumber'] = $poNumber;

		$this->data['obj'] 	= PO::where('po_number', $poNumber)->first();
		if ($this->data['obj'] == null) {
			$this->data['alert'] = (object) ['message'=>'PO Number Not Found !', 'type'=>'danger'];
			return view($this->VIEW_PATH.'po-monitor', $this->data);
		}

		$detailPO = $this->data['obj']->detail;
		foreach ($detailPO as $key => $detail) {
			
			$detail->total_qty = ($detail->unit_qty * $detail->qty);
			$detail->output = $detail->getProductionOutput();

            $detail->percentage = ($detail->output * 100) / $detail->total_qty;
			if ($detail->mark_done > 0) $detail->percentage = 100;

			$color = "danger";
            if ($detail->percentage == 100) $color = "primary";
            else if ($detail->percentage > 60) $color = "success";
            else if ($detail->percentage > 30) $color = "warning";
            $detail->color = $color;

		}
		$this->data['detailPO'] = $detailPO;

		// DB::enableQueryLog();
		// $x = $obj->mandaysReport;
		// $query = DB::getQueryLog();
		// print_r($query);
		// dd('stop');

		return view($this->VIEW_PATH.'po-monitor', $this->data);
	}

	public function production()
	{
		$this->data['employees'] = Employee::all();
		$this->data['pos'] 		 = PO::all();
		return view($this->VIEW_PATH.'production', $this->data);
	}

	public function productionSave(Request $req)
	{
		// dd('coming soon');
		// dd($req->all());
		
		$picId 		= $req->input('picId');
		$reportDate = $req->input('reportDate');
		$outputList = $req->input('output');  // array

		$records = array();
		foreach ($outputList as $poNumber => $po) {
			
			foreach ($po as $partNumber => $output) {
				
				if ($output <= 0) continue; // skip unreported progess

				$records[] = array(
					'reported_date' => $reportDate,
					'reported_by' 	=> $picId,
					'po_number' 	=> $poNumber,
					'part_number' 	=> $partNumber,
					'qty_output' 	=> $output,
					'created_at'	=> date('Y-m-d H:i:s')
				);

			}
			
		}

		// dd($records);

		// DB::beginTransaction();
		$isSuccess = DB::table('production_report')->insert($records);

		if ($isSuccess) {
			// DB::commit();
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'Production Report saved !', 'type'=>'success']);
		} else {
			// DB::rollBack();
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to save Production Report !', 'type'=>'danger']);
		}

	}

	public function mandays()
	{
		$this->data['employees'] = Employee::all();
		$this->data['pos'] 		 = PO::all();
		$this->data['mandays']	 = \App\Model\MandaysReport::orderBy('reported_date', 'desc')->get();
		return view($this->VIEW_PATH.'mandays', $this->data);
	}

	public function mandaysSave(Request $req)
	{
		// dd('coming soon');
		// dd($req->all());

		$picId 		= $req->input('picId'); // kepala regu
		$reportDate = $req->input('reportDate');
		$shift 		= $req->input('shift');
		$employees 	= $req->input('employees');

		$manhourList = array();
		foreach ($req->input('mh') as $idx => $mh) {
			
			if ($mh == 0) continue; // skip unreported progress

			$manhourList[] = array(
				'reported_date' => $reportDate,
				'reported_by' 	=> $picId,
				'employee_id' 	=> $employees[$idx],
				'man_hour' 		=> $mh,
				'shift' 		=> $shift,
				'created_at'	=> date('Y-m-d H:i:s')
			);
		}
		// dd($manhourList);

		$isSuccess = DB::table('mandays_report')->insert($manhourList);

		if ($isSuccess) {
			// DB::commit();
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'Mandays Report saved !', 'type'=>'success']);
		} else {
			// DB::rollBack();
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to save Mandays Report !', 'type'=>'danger']);
		}
	}

	public function delete(Request $req)
	{
		$poNumber = $req->input('deletedPONumber');

		$obj = \App\Model\PO::where('po_number', $poNumber)->first();
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		if ($obj->delete()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'delete PO success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to delete PO !', 'type'=>'danger']);
		}
	}

	public function deleteItem(Request $req)
	{
		$poNumber = $req->input('poNumberRef');
		$itemCode = $req->input('deletedItemCode');

		$redirectTo = $this->BASE_PATH.'/edit/'.base64_encode($poNumber);

		DB::beginTransaction();
		$resultDeleteDetail 	= \App\Model\PODetail::where('po_number', $poNumber)->where('item_code', $itemCode)->delete();
		$resultDeletePrice 	= \App\Model\POPrice::where('po_number', $poNumber)->where('item_code', $itemCode)->delete();

		if ($resultDeleteDetail && $resultDeletePrice) {
			DB::commit();
			return redirect($redirectTo)->with('alert', ['message'=>'delete item '. $itemCode .' success !', 'type'=>'success']);
		} else {
			DB::rollBack();
			return redirect($redirectTo)->with('alert', ['message'=>'<b>failed</b> to delete item '. $itemCode .' !', 'type'=>'danger']);
		}
	}
}
