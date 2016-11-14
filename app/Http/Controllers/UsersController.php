<?php

namespace App\Http\Controllers;

use App\Capabilities;
use App\UserCapabilities;
use DB;
use Gate;
use \App\User;
use Auth;
use App\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Html\HtmlServiceProvider;
use Illuminate\Html\FormFacade;
use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Session;


class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // check if user has employees capability
        if(User::userCan('employees') === false)
        {
            abort(403, 'Unauthorized action.');
        }
    }
    /**
     * handle eagleeye users
     *
     */


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //users listing
        $users = User::getAllUsers();

        return view('users.users', [ 'users' => $users ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $rolesArray = User::getRolesArray();
		$status     = array('active' => 'active', 'inactive' => 'inactive');
        return view('users.newuser', ['rolesArray' => $rolesArray, 'status' => $status]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {


        $this->validate($request, [
            'name'       => 'required',
            'username'   => 'required|alpha_num|unique:users',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|confirmed',
            'role'       => 'required|numeric'
        ]);

        $input = $request->all();

        $role_name = UserRole::getRoleName($input['role']);

        $user = $this->saveToDb($input);
        $user->setCapabilities($role_name->role_name);


        Session::flash('flash_message', 'Employee successfully created!');

        return redirect('users');

    }

    /*
     * save user to database
     *
     * */

    protected function saveToDb(array $data)
        {

            return $user =  User::create([
                'name'      => $data['name'],
                'username'  => $data['username'],
                'email'     => $data['email'],
                'password'  => bcrypt($data['password']),
                'role_id'   => $data['role'],
                'status'    => $data['status'],
            ]);
        }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id, Request $request, User $user)
    {
        //edit view page


        $userInfo = User::with('capabilities')
            ->where('id', $id)
            ->first();

        $user_capabilities = array();
        foreach ($userInfo->capabilities as $capability)
        {
            $user_capabilities[] = $capability->capability;
        }

        $select = User::getRolesArray();
        $status = array('active' => 'active', 'inactive' => 'inactive');

        $capabilities = Capabilities::all();


        return view('users.edituser', ['userInfo' => $userInfo, 'select' => $select, 'status' => $status, 'capabilities' => $capabilities, 'user_capabilities' => $user_capabilities]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $input = $request->all();

        if($input['action'] == 'update_info') {

            $this->validate($request, [
                'name' => 'required',
                'username' => 'required|alpha_num',
                'email' => 'required|email',
                'role_id' => 'required|numeric',
                'status' => 'required'
            ]);
        }else{
            if(isset($input['capabilities'])) {
                $user->capabilities()->sync($input['capabilities']);
            }else{
                $user->capabilities()->sync([]);
            }

            return redirect()->back()->with('flash_message','Capabilities updated!');

        }



        $user->fill($input)->save();

        Session::flash('flash_message', 'Employee successfully updated!');
        return redirect('/users');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //delete the user
        //dd($id);
    }




    /**
     * get user capabilities
     *
     * @param int $id
     *
     * @return response
     */

    public function getCapabilities($id){

        $capabilities = User::find($id)->capabilities;
        return $capabilities;
    }


}
