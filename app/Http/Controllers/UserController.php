<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as Obj;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class UserController extends Controller
{
	use AuthenticatesUsers;

	protected $BASE_PATH = '/user';
	protected $VIEW_PATH = 'modules.user.';

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
		$data['roles'] = \App\Model\Role::get();
		return view($this->VIEW_PATH.'form')->with('data', (Object) $data);
	}
    
	public function save(Request $req)
	{
		$validatedData = $req->validate([
	        'email' 	=> 'bail|required|unique:users',
	        'name' 		=> 'required',
	        'password' 	=> 'required|same:confirm_password',
	        'role_id' 	=> 'required',
	    ]);

		$obj = new Obj;
		$obj->email  	= $req->input('email');
		$obj->name 		= $req->input('name');
		$obj->password 	= Hash::make($req->input('password'));
		$obj->role_id 	= $req->input('role_id');

		if ($obj->save()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'add new user success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to add new user !', 'type'=>'danger']);
		}
	}
    
	public function edit($id)
	{
		$obj = Obj::find($id);
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		$obj->roles = \App\Model\Role::get();
		return view($this->VIEW_PATH.'form-edit')->with('obj', $obj);
	}
    
	public function update(Request $req)
	{
		$id = $req->input('id');

		$obj = Obj::find($id);
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		$obj->name 		= $req->input('name');
		$obj->role_id 	= $req->input('role_id');

		if ($obj->save()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'edit user success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to edit user !', 'type'=>'danger']);
		}
	}

	public function delete(Request $req)
	{
		$id = $req->input('deletedId');

		$obj = Obj::find($id);
		if ($obj == null) return redirect($this->BASE_PATH); // data not found

		if ($obj->delete()) {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'delete user success !', 'type'=>'success']);
		} else {
			return redirect($this->BASE_PATH)->with('alert', ['message'=>'<b>failed</b> to delete user !', 'type'=>'danger']);
		}
	}

	public function changePassword(Request $req)
	{		
		$validatedData = $req->validate([
	        'old_password' 	=> 'required',
	        'new_password' 	=> 'required|same:confirm_password'
	    ]);

		if (Hash::check($req->input('old_password'), auth()->user()->password)) {
			$currentUser = Obj::find(auth()->user()->id);
			$currentUser->password = Hash::make($req->input('new_password'));
			
			if ($currentUser->save()){
				return redirect('/change-password')->with('alert', ['message'=>'Change password success, please re-login to application using your new password !', 'type'=>'success']);	
			} else {
				return redirect('/change-password')->with('alert', ['message'=>'Change password fail', 'type'=>'danger']);
			}

		} else {			
			return redirect('/change-password')->with('alert', ['message'=>'Your current password invalid !', 'type'=>'danger']);
		}
	}
}
