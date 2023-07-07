<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class UserModel extends Model
{

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['role','name','email','last_login'];
    protected $hidden = ['remember_token','password'];
    public $timestamp = false;
    
    //
    public static function getUsers()
    {
        return DB::table('users')->orderBy('created_at','desc')->paginate();
    }

    public static function user($id)
    {
        return DB::table('users')->where('id',$id)->first();
    }

    public static function addData()
    {
        return  DB::table('users')->insert([
            'name' => Input::get('name'),
            'email' => Input::get('email'),
            'password' => bcrypt(Input::get('password')),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function editData()
    {
        return  DB::table('users')->where('id',Input::get('id'))
        ->update([
            'name' => Input::get('name'),
            'email' => Input::get('email'),
            'password' => bcrypt(Input::get('password')),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function deleteData()
    {
        return DB::table('users')->where('id',Input::get('id'))->delete();
    }

    public static function stampLogin($id)
    {
        return  DB::table('users')->where('id',$id)->update([
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }
}
