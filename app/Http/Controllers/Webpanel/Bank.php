<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\BankModel;

class Bank extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'bank';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = BankModel::orderBy('sort','asc')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('bank','like',"%{$keyword}%")
                ->orWhere('bank_code','like',"%{$keyword}%");
            });

        if($view=='all'){
            $rows = $query->get();
        }else{
            $view = ($request->view)? $view : 10 ;
            $rows = $query->paginate($view);
            $rows->appends(['view' => $request->view,'keyword' => $keyword,'page' => $request->page]);
        }

      
       return view("$this->prefix.pages.$this->folder.index",[
            'css'=> ['public/back-end/css/table-responsive.css'],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"/back-end/build/$this->folder.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'index',
            'segment' => "$this->segment",
            'rows' => $rows
        ]);
    }

    public function create()
    {
        return view("$this->prefix.pages.$this->folder.index",[
            'css'=> ['public/back-end/css/table-responsive.css','public/back-end/css/validate.css'],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"/back-end/build/$this->folder.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'add',
            'segment' => $this->segment,
        ]);
    }

    public function store(Request $request)
    {
        $bank = new BankModel;
        $bank->name = $request->name;
        $bank->bank_code = $request->bank_code;
        $bank->branch = $request->branch;
        $bank->account = $request->account;
        $bank->account_type = $request->account_type;
        $bank->status = 'on';
        $bank->sort = 1;
        $bank->created_at = date('Y-m-d H:i:s');
        $bank->updated_at = date('Y-m-d H:i:s');
        if($bank->save())
        {
            //new sort
            BankModel::where('id','!=',$bank->id)->increment('sort');
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = BankModel::find($id);
        return view("$this->prefix.pages.$this->folder.index",[
            'css'=> ['public/back-end/css/table-responsive.css'],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"/back-end/build/$this->folder.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'edit',
            'segment' => $this->segment,
            'row' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $bank = BankModel::find($id);
       $bank->name = $request->name;
        $bank->bank_code = $request->bank_code;
        $bank->branch = $request->branch;
        $bank->account = $request->account;
        $bank->account_type = $request->account_type;
        $bank->status = 'on';
        $bank->sort = 1;
     
        $bank->updated_at = date('Y-m-d H:i:s');
       
        if($bank->save())
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

 

    public function status(Request $request)
    {
        $get = BankModel::find($request->id);
        if(@$get->id){
            $status = ($get->status=='off')? 'on' : 'off' ;
            $get->status = $status;
            $get->save();
            if($get->id){
                return response()->json(true);
            }else{
                return response()->json(false);
            }
        }
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        
        BankModel::where('id',$id)->delete();
        return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
    }

  
}
