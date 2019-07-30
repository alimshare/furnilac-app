<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Item as Obj;
use App\Exports\PartExport;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
	protected $BASE_PATH = '/item';
	protected $VIEW_PATH = 'modules.item.';

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
		return view('modules.item.list')->with('list', $list);
	}
    
	public function new()
	{
		return view($this->VIEW_PATH.'form');
	}
    
	public function save(Request $req)
	{
		$obj = new Obj;
		$obj->item_code = $req->input('item_code');
		$obj->item_name = $req->input('item_name');

		if ($obj->save()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'save new item success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to save new item !', 'type'=>'danger']);
		}
	}
    
	public function edit($code)
	{
		$obj = Obj::where('item_code', $code)->first();
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		return view($this->VIEW_PATH.'form-edit')->with('object', $obj);
	}
    
	public function update(Request $req)
	{
		$code = $req->input('item_code');

		$obj = Obj::where('item_code', $code)->first();
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		$obj->item_name = $req->input('item_name');
		if ($obj->save()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'edit item success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to edit item !', 'type'=>'danger']);
		}
	}

	public function delete(Request $req)
	{
		$code = $req->input('deletedCode');

		$obj = Obj::where('item_code', $code)->first();
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		if ($obj->delete()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'delete item success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to delete item !', 'type'=>'danger']);
		}
	}

	public function export()
	{
		return Excel::download(new PartExport, 'item-parts.xlsx');
	}

	public function part_list($item_code){
		$list = \App\Model\Part::where('item_code', $item_code)->get();
		$list->item_code = $item_code;
		return view('modules.item.part')->with('list', $list);
	}
    
	public function part_new($item_code)
	{
		$item = Obj::where('item_code', $item_code)->first();
		if ($item == null) return redirect($this->BASE_PATH); // data not found


		return view('modules.part.form')->with('item_code', $item_code);
	}
    
	public function part_save(Request $req)
	{
		$item = Obj::where('item_code', $req->input('item_code'))->first();
		if ($item == null) return redirect($this->BASE_PATH); // data not found

		$obj = new  \App\Model\Part;

		$obj->item_code 	= $req->input('item_code');
		$obj->part_number 	= $req->input('part_number');
		$obj->part_name 	= $req->input('part_name');
		$obj->price	 		= $req->input('price');
		$obj->qty	 		= $req->input('qty');

		if ($obj->save()) {
			return redirect($this->BASE_PATH.'/'.$obj->item_code)->with('alert', ['message'=>'save part success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH.'/'.$obj->item_code)->with('alert', ['message'=>'<b>failed</b> to save part !', 'type'=>'danger']);
		}

		return view('modules.part.form')->with('item_code', $item_code);
	}
    
	public function part_edit(Request $req, $item_code, $part_number)
	{
		$item = Obj::where('item_code', $item_code)->first();
		if ($item == null) return redirect($this->BASE_PATH); // data not found]

		$part = \App\Model\Part::where('part_number', $part_number)->first();
		if ($item == null) return redirect($this->BASE_PATH); // data not found]

		// dd($part);

		return view('modules.part.form-edit')->with('object', $part);
	}
    
	public function part_update(Request $req)
	{
		$partNumber = $req->input('part_number');

		$obj = \App\Model\Part::where('part_number', $partNumber)->first();
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		$obj->part_name 	= $req->input('part_name');
		$obj->price	 		= $req->input('price');
		$obj->qty	 		= $req->input('qty');

		if ($obj->save()) {
			return redirect($this->BASE_PATH.'/'.$obj->item_code)->with('alert', ['message'=>'edit part success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH.'/'.$obj->item_code)->with('alert', ['message'=>'<b>failed</b> to edit part !', 'type'=>'danger']);
		}
	}

	public function part_delete(Request $req)
	{
		$partNumber = $req->input('deletedId');
		$redirectPath = $this->BASE_PATH.'/'.$req->input('item_code');

		$obj = \App\Model\Part::where('part_number', $partNumber)->first();
		if ($obj == null) return redirect($redirectPath); // data not found

		if ($obj->delete()) {
			return redirect($redirectPath)->with('alert', ['message'=>'delete part success !', 'type'=>'success']);
		} else {
			return redirect($redirectPath)->with('alert', ['message'=>'<b>failed</b> to delete part !', 'type'=>'danger']);
		}
	}
}
