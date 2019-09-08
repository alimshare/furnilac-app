<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\PO as Obj;
use App\Model\Item;
use App\Model\Employee;

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
		$list = Obj::all();
		return view($this->VIEW_PATH.'list')->with('list', $list);
	}
    
	public function new()
	{
		$this->data['item'] = Item::all();
		$this->data['employees'] = Employee::all();
		return view($this->VIEW_PATH.'form', $this->data);
	}

	public function save(Request $request)
	{
		dd($request->all());
	}

	public function productionSearch()
	{
		return view($this->VIEW_PATH.'production-search');
	}
}
