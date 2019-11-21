<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Model\ReportPeriod;
use App\Model\ReportPeriodPrice;

class ReportPeriodController extends Controller
{
	protected $BASE_PATH = '/report-period';
	protected $VIEW_PATH = 'modules.report-period.';

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
		$list = ReportPeriod::all();
		return view('modules.report-period.list')->with('list', $list);
	}
    
	public function new()
	{
		return view($this->VIEW_PATH.'form');
	}
    
	public function save(Request $req)
	{
		$obj = new ReportPeriod;
		$obj->start_period = $req->input('start_period');
        $obj->end_period = $req->input('end_period');


		if ($obj->save()) {

			$items = DB::select("select ? period_id,item.id item_id, part.id part_id, part.price, now() as created_at from item inner join part on item.item_code = part.item_code where item.deleted_at is null and part.deleted_at is null", array($obj->id));		

			$data = [];
			foreach ($items as $key => $value) {
				$data[] = [
					'item_id' => $value->item_id,
					'part_id' => $value->part_id,
					'price' => $value->price,
					'period_id' => $value->period_id,
					'created_at' => $value->created_at
				];
			}

			ReportPeriodPrice::insert($data);

			return redirect($this->BASE_PATH)->with('alert', ['message'=>'save new period success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to save new period !', 'type'=>'danger']);
		}
	}
    
	public function edit($id)
	{
		$obj = ReportPeriod::find($id);

		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		$data['obj'] = $obj;
		$data['prices'] = ReportPeriodPrice::where('period_id', $obj->id)->get();

		return view($this->VIEW_PATH.'form-edit', $data);
	}
    
	public function update(Request $req)
	{
		$id = $req->input('id');

		$obj = Obj::find($id);
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

        $obj->name = $req->input('name');
        $obj->section = $req->input('section');

		if ($obj->save()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'edit group success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to edit group !', 'type'=>'danger']);
		}
	}

	public function delete(Request $req)
	{
		$id = $req->input('deletedId');
		$obj = ReportPeriod::find($id);
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		DB::beginTransaction();
		if (ReportPeriodPrice::where('period_id', $obj->id)->delete() && $obj->delete()) {
			DB::commit();
			// die('success');
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'delete report period success !', 'type'=>'success']);
		} else {
			DB::rollBack();
			// die('fail');
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to delete report period !', 'type'=>'danger']);
		}
	}
}
