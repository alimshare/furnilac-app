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
		
		$poPrices = array();
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

		dd($orderList);
		dd($orderList);
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

	public function monitor($poNumber = "")
	{

		$this->data['poNumber'] = $poNumber;
		if ($poNumber == "") return view($this->VIEW_PATH.'po-monitor', $this->data);

		$this->data['obj'] 	= PO::find($poNumber);
		if ($this->data['obj'] == null) {
			$this->data['alert'] = (object) ['message'=>'PO Number <strong>'.$poNumber.'</strong> Not Found !', 'type'=>'danger'];
			return view($this->VIEW_PATH.'po-monitor', $this->data);
		}

		$detailPO = $this->data['obj']->detail;
		foreach ($detailPO as $key => $detail) {
			
			$detail->total_qty = ($detail->unit_qty * $detail->qty);
			$detail->output = $detail->getProductionOutput();
            $detail->percentage = ($detail->output * 100) / $detail->total_qty;

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
}
