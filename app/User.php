<?php

namespace App;

use Doctrine\Instantiator\Exception\UnexpectedValueException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use DB;
use League\Flysystem\Config;

class User extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'role_id', 'status' ,'id'
    ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    //
    protected $dates     = ['created_at'];

    protected $connection = 'eagleeye';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = 'eagleeye'; 
    }

    // save User form any other controller

    public function saveUser($data)
    {
        if(!empty($data))
        {
            if(DB::table('customers')->insert($data))
            {
                return  DB::getPdo()->lastInsertId();
            }
        }

        return false; 

    }

    // end


    public static function getAllUsers()
    {

        return DB::connection('eagleeye')->table('users')->orderBy('id', 'desc')->paginate(10);
    }

    // get user role

    public function role()
    {
        return $this->hasOne('App\Role', 'role');
    }
    public function posts()
    {
        return $this->hasMany('App\Posts','author' ,'id');
    }
    public function media()
    {
        return $this->hasOne('App\Media', 'id' , 'uploaded_by');
    }



    public static function getRolesArray()
    {
        $select = UserRole::all();

        foreach($select as $role)
        {
            $selectArray[$role->id] = ($role->role_name);
        }

        return $selectArray;
    }


    public function capabilities()
    {
        return $this->belongsToMany('App\Capabilities');
    }

    public function getUserCapabilities()
    {
        return $this->capabilities();
    }



    public function isEmployee(){

        //return count($this->with('capabilities')->get());
        return (Auth::user()->capabilities->count()) ? true : false;
    }

    public static function userCan($action)
    {
        if (Auth::check()) {
            return Auth::user()->capabilities->pluck("capability")->contains($action);
        }else{
            return redirect('login');
        }
    }

    private function getIdInArray($array, $term){
        foreach($array as $key => $value){
            if($value == $term)
            {
                return $key;
            }
        }

        throw new UnexpectedValueException;
    }


    public function setCapabilities($role)
    {
        $assigned_capabilities = array();
        $capabilities = Capabilities::all()->pluck('capability', 'id');

        $admin_capabilities = $this->administratorCapabilities();
        $editor_array = $this->editorCapabilities();

        switch($role)
        {
            case 'administrator': {
                foreach ($admin_capabilities as $admin_cap) {

                    $assigned_capabilities[] = $this->getIdInArray($capabilities, $admin_cap->capability);
                }

            }
            case 'editor':
                foreach($editor_array as $editor_cap)
                {
                    $assigned_capabilities[] = $this->getIdInArray($capabilities, $editor_cap);
                }

                break;
            default:
                throw new \Exception('Role does not exist');
        }


        $this->capabilities()->sync($assigned_capabilities);
    }

    public function administratorCapabilities(){
        return Capabilities::all();
    }


    public function editorCapabilities(){
        return array(
            'posts',
            'pages',
            'themusthaves.nl',
            'themusthaves.de',
            'musthavesforreal.com'
        );
    }


    public function checkIfExist($field = 'id', $field_value = false)
    {
        $count  = false ;
        if($field_value)
        {
            if($this::Where($field, '=', $field_value)->get()->count() > 0)
                $count  = true ;
        } 
        return $count;
    }

    public function getUserId($field = 'email', $field_value = false)
    {
        $id = false;
        if($field_value)
        {
            $user   =  $this::select('id')->where($field, $field_value)->first();

            if($user)
            {
                $id     = $user->id;
            }

        }

        return $id;
    }

    public function getUserInfo($fields = '*', $user_id = false)
    {
        if(is_array($fields)  && $user_id)
        {
            $str = '';
            foreach ($fields as $field)
            {
                $str .= "'".$field."',";
            }

            $str  = rtrim($str, ',');

            return $this::select($fields)->where('id', $user_id)->first();
        }else{
            return $this::where('id', $user_id)->first();
        }

    }



}
