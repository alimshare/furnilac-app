<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Group as Obj;

class GroupController extends Controller
{
	protected $BASE_PATH = '/group';
	protected $VIEW_PATH = 'modules.group.';

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
		return view('modules.group.list')->with('list', $list);
	}
    
	public function new()
	{
		return view($this->VIEW_PATH.'form');
	}
    
	public function save(Request $req)
	{
		$obj = new Obj;
		$obj->name = $req->input('name');
        $obj->section = $req->input('section');

		if ($obj->save()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'save new group success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to save new group !', 'type'=>'danger']);
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

		$obj = Obj::find($id);
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		if ($obj->delete()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'delete group success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to delete group !', 'type'=>'danger']);
		}
	}
}
