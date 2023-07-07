<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ClientModel;

class Client extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'client';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = ClientModel::orderBy('sort','asc')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('name','like',"%{$keyword}%");
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
                ["type"=>"text/javascript","src"=>"/back-end/build/vendor.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'index',
            'segment' => $this->segment,
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
                ["type"=>"text/javascript","src"=>"/back-end/build/vendor.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'add',
            'segment' => $this->segment,
        ]);
    }

    public function store(Request $request)
    {
        $data = new ClientModel;
        $data->name = $request->name;
        $data->tax_number = $request->tax_number;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->bank_name = $request->bank_name;
        $data->bank_account = $request->bank_account;
        $data->bank_number = $request->bank_number;
        $data->status = 'on';
        $data->sort = 1;
        $data->created = date('Y-m-d H:i:s');
        $data->updated = date('Y-m-d H:i:s');
        if($data->save())
        {
            //new sort
            ClientModel::where('id','!=',$data->id)->increment('sort');
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
        $data = ClientModel::find($id);
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
            'page' => 'edit',
            'segment' => $this->segment,
            'row' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = ClientModel::find($id);
        $data->name = $request->name;
        $data->tax_number = $request->tax_number;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->bank_name = $request->bank_name;
        $data->bank_account = $request->bank_account;
        $data->bank_number = $request->bank_number;
        $data->updated = date('Y-m-d H:i:s');
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
        $datas = ClientModel::find($id);
        if(@$datas)
        {
            foreach($datas as $data)
            {
                //new sort
                ClientModel::where('sort','>',$data->sort)->decrement('sort', 1);
                $query = ClientModel::destroy($data->id);
            }
        }
        
        if($query){
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }

    public function status(Request $request)
    {
        $get = ClientModel::find($request->id);
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

    public function dragsort(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $get = ClientModel::find($request->id);
        if($from!="" && $to !="")
        {
            if($from > $to){
                ClientModel::whereBetween('sort', [$to, $from])->whereNotIn("id",[$get->id])->increment('sort', 1);
            }else{
                ClientModel::whereBetween('sort', [$from, $to])->whereNotIn("id",[$get->id])->decrement('sort', 1);
            }
            $query = ClientModel::where('id',$get->id)->update(['sort'=>$to]);
            return response()->json($query);
        }
        return response()->json(false);
        
    }
}
