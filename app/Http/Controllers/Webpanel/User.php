<?php

namespace App\Http\Controllers\Webpanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserModel;

class User extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'user';

    public function index()
    {
        return view("$this->prefix.pages.user.index",[            
            'js' => [
                ['type'=>"text/javascript",'src'=>"public/back-end/js/jquery.slim.min.js",'class'=>"view-script"],
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ["type"=>"text/javascript","src"=>"public/back-end/build/user.js"],
            ],
            'prefix' => $this->prefix,
            'segment' => $this->segment,
            'folder' => 'user',
            'page' => 'index',
            'controller' => 'user',
            'rows' => UserModel::where('role' , '!=' , 'superadmin2')->paginate(10)
        ]);
    }
    
    public function edit(Request $request,$id)
    {
        return view("$this->prefix.pages.user.index",[
            'css'=> [
                "public/back-end/css/select2.min.css",
                "public/back-end/css/select2-coreui.min.css",
                "public/back-end/css/bootstrap-select.min.css",
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/js/select2.min.js","clas"=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ["src"=>"public/back-end/js/bootstrap-select.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/user-manage-validate.js"],
                
            ],
            'prefix' => $this->prefix,
            'folder' => 'user',
            'page' => 'edit',
            'segment' => $this->segment,
            'row' => UserModel::find($id)
        ]);
    }

    public function create()
    {
        return view("$this->prefix.pages.user.index",[
            'css'=> [
                "public/back-end/css/select2.min.css",
                "public/back-end/css/select2-coreui.min.css",
                "public/back-end/css/bootstrap-select.min.css",
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/js/select2.min.js","clas"=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ["src"=>"public/back-end/js/bootstrap-select.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/user-manage-validate.js"],
                
            ],
            'prefix' => $this->prefix,
            'segment' => $this->segment,
            'folder' => 'user',
            'page' => 'add',
            'segment' => 'user',
        ]);
    }

    public function reset($id)
    {
        return view("$this->prefix.pages.user.index",[
            'css'=> [
                "public/back-end/css/select2.min.css",
                "public/back-end/css/select2-coreui.min.css",
                "public/back-end/css/bootstrap-select.min.css",
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/js/select2.min.js","clas"=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ["src"=>"public/back-end/js/bootstrap-select.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/user-manage-validate.js"],
                
            ],
            'prefix' => $this->prefix,
            'folder' => 'user',
            'page' => 'reset',
            'segment' => 'user',
            'row' => UserModel::find($id)
        ]);
    }

    public function onReset(Request $request,$id=null)
    {
        $data = \App\UserModel::find($id);
        if($request->username){
            $data->username = $request->username;
        }
        $data->password = bcrypt($request->password);
        $data->updated_at = date('Y-m-d H:i:s');
        if($data->save())
        {
            return view("$this->prefix/alert/sweet/success",['url'=>$request->fullUrl()]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function store(Request $request)
    {
        $data = new UserModel;
        $data->role = $request->role;
        $data->status = $request->status;
        $data->name = $request->name;
        $data->email = $request->username;
        $data->permission = $request->permission;
        $data->password = bcrypt($request->password);
        $data->created_at = date('Y-m-d H:i:s');
        if($data->save())
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function update(Request $request, $id=null)
    {
        $data = UserModel::find($id);
        $data->role = $request->role;
        $data->status = $request->status;
        $data->name = $request->name;
        $data->permission = $request->permission;
        if(strtolower($request->role)=='superadmin')
        {
           $data->permission=1; 
        }
        $data->updated_at = date('Y-m-d H:i:s');
        if($data->save())
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function destroy(Request $request)
    {
        $id = explode(',',$request->id);
        $datas = UserModel::find($id);
        if(@$datas)
        {
            foreach($datas as $data)
            {
                $query = UserModel::destroy($data->id);
            }
        }
        
        if($query){
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }

    public function exist(Request $request)
    {
        $data = \App\UserModel::where('email',$request->username)->count();
        if($data){ 
            return response()->json(false); 
        }else{ 
            return response()->json(true);
        }
    }

    public function checkUserOnReset(Request $request)
    {
        $data = \App\UserModel::where('email','!=',$request->username)->count();
        if($data){ 
            // return response()->json(false); 
            $check = \App\UserModel::where('email',$request->username)->count();
            if($check){
                return response()->json(false);
            }else{
                return response()->json(true);
            }
        }else{ 
            return response()->json(true);
        }
    }
}
