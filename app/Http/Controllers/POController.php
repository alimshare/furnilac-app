<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\PO as Obj;

class POController extends Controller
{
    protected $BASE_PATH = '/po';
	protected $VIEW_PATH = 'modules.po.';

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

}
