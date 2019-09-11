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


		$itemList = $request->input('itemCode');
		$qtyList  = $request->input('qty');
		
		$orderList = array();
		for ($i=0; $i < count($itemList); $i++) { 
			
			$itemCode = $itemList[$i];
			$partList = Part::where('item_code', $itemCode)->select('part_number','price','qty')->get();

			foreach ($partList as $key => $part) {				
				$detail = array();
				$detail['po_number'] 	= $obj->po_number;
				$detail['part_number'] 	= $part->part_number;
				$detail['unit_qty'] 	= $part->qty;
				$detail['price'] 		= $part->price;
				$detail['qty'] 			= $qtyList[$i];
				$detail['created_at']	= date('Y-m-d H:i:s');
				$orderList[] = $detail;
			}

		}

		// dd($orderList);

		DB::beginTransaction();

		$isSuccessPo = $obj->save();
		$isSuccessDetail = DB::table('po_detail')->insert($orderList);

		if ($isSuccessPo && $isSuccessDetail) {
			DB::commit();
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'save new PO success !', 'type'=>'success']);
		} else {
			DB::rollBack();
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to save new PO !', 'type'=>'danger']);
		}

	}

	public function production($poNumber = "")
	{
		$obj = PO::find($poNumber);

		// DB::enableQueryLog();
		// $x = $obj->mandaysReport;
		// $query = DB::getQueryLog();
		// print_r($query);
		// dd('stop');

		$result =  view($this->VIEW_PATH.'production-search')->with('obj', $obj)->with('poNumber', $poNumber);

		if ($obj == null && $poNumber != '') {
			return $result->with('alert', (object) ['message'=>'PO Number <strong>'.$poNumber.'</strong> Not Found !', 'type'=>'danger']);
		}

		return $result;
	}

	public function productionReport($poNumber)
	{
		$this->data['po'] = PO::find($poNumber);
		if ($this->data['po'] == null) return redirect($this->BASE_PATH.'/production'); // po not found, redirect to production progress index

		// foreach ($this->data['po']->detail as $key => $value) {
		// 	dd($value->getProductionOutput());
		// }

		$this->data['employees'] = Employee::all();
		return view($this->VIEW_PATH.'production-report', $this->data);
	}
}
