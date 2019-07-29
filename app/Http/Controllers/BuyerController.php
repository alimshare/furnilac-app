<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Buyer as Obj;
use App\Exports\BuyerExport;
use Maatwebsite\Excel\Facades\Excel;

class BuyerController extends Controller
{
	protected $BASE_PATH = '/buyer';
	protected $VIEW_PATH = 'modules.buyer.';

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
		$list = Obj::all();
		return view('modules.buyer.list')->with('list', $list);
	}
    
	public function new()
	{
		return view($this->VIEW_PATH.'form');
	}
    
	public function save(Request $req)
	{
		$obj = new Obj;
		$obj->name = $req->input('name');

		if ($obj->save()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'save new buyer success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to save new buyer !', 'type'=>'danger']);
		}
	}
    
	public function edit($id)
	{
		$obj = Obj::find($id);
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		return view($this->VIEW_PATH.'form-edit')->with('obj', $obj);
	}
    
	public function update(Request $req)
	{
		$id = $req->input('id');

		$obj = Obj::find($id);
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		$obj->name = $req->input('name');

		if ($obj->save()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'edit buyer success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to edit buyer !', 'type'=>'danger']);
		}
	}

	public function delete(Request $req)
	{
		$id = $req->input('deletedId');

		$obj = Obj::find($id);
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		if ($obj->delete()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'delete buyer success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to delete buyer !', 'type'=>'danger']);
		}
	}

	public function export()
	{
		return Excel::download(new BuyerExport, 'buyer.xlsx');
	}
}
