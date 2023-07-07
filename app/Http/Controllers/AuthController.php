<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Session;

class AuthController extends Controller
{
    protected $prefix = 'back-end';
    
    public function getLogin()
    {
        return view("$this->prefix.auth.login",[
            'css' => [""],
            'prefix' => $this->prefix
        ]);
    }
    public function postLogin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $remember = ($request->remember=='on')? true : false ;
        if(Auth::attempt(['email'=>$username,'password'=>$password,'status'=>'active'],$remember))
        {
            $id = Auth::user()->id;
            $data = \App\UserModel::stampLogin($id);
            $u=DB::table('users')->where('id',$id)->first();

            session::put("permission",$u->permission);
       
            // $disallow = url('webpanel/login');
            // if($request->referer!='' && $request->referer!=$disallow){
            //     $url = $request->referer;
            // }else{
            //     $url = url('webpanel');
            // }
            return redirect('/dashboard');
        }else{
            return redirect('login')->with(['error'=>'Username or Password is incorrect!']);
        }

    }

    public function logOut()
    {
        if(!Auth::logout())
        {
            return redirect("login");
        }
    }
}
