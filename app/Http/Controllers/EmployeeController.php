<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Employee as Obj;
use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{

	protected $BASE_PATH = '/employee';
	protected $VIEW_PATH = 'modules.employee.';

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
		return view($this->VIEW_PATH.'list')->with('list', $list);
	}
    
	public function new()
	{
		return view($this->VIEW_PATH.'form');
	}
    
	public function save(Request $req)
	{
		$obj = new Obj;
		$obj->nik  = $req->input('nik');
		$obj->name = $req->input('name');

		if ($obj->save()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'save new employee success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to save new employee !', 'type'=>'danger']);
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

		$obj->nik  = $req->input('nik');
		$obj->name = $req->input('name');

		if ($obj->save()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'edit employee success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to edit employee !', 'type'=>'danger']);
		}
	}

	public function delete(Request $req)
	{
		$id = $req->input('deletedId');

		$obj = Obj::find($id);
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		if ($obj->delete()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'delete employee success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to delete employee !', 'type'=>'danger']);
		}
	}

	public function export()
	{
		return Excel::download(new EmployeeExport, 'employees.xlsx');
	}

}
