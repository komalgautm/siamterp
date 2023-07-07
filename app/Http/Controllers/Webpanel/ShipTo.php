<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ShipToModel;

class ShipTo extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'shipto';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = ShipToModel::orderBy('sort','asc')
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
            'css'=> [
                'public/back-end/css/table-responsive.css',
                'public/back-end/css/select2.min.css'
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ["src"=>'public/back-end/js/select2.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/$this->folder.js"],
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
            'css'=> [
                'public/back-end/css/table-responsive.css',
                'public/back-end/css/validate.css',
                'public/back-end/css/select2.min.css'
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ["src"=>'public/back-end/js/select2.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/$this->folder.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'add',
            'segment' => $this->segment,
        ]);
    }

    public function store(Request $request)
    {
        $data = new ShipToModel;
        $data->client = $request->client;
        $data->code = $request->code;
        $data->name = $request->name;
        $data->tax_number = $request->tax_number;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->airport = $request->airport;
        $data->notify_name = $request->notify_name;
        $data->notify_tax_number = $request->notify_tax_number;
        $data->notify_email = $request->notify_email;
        $data->notify_phone = $request->notify_phone;
        $data->notify_address = $request->address;
        $data->status = 'on';
        $data->sort = 1;
        $data->created = date('Y-m-d H:i:s');
        $data->updated = date('Y-m-d H:i:s');
        if($data->save())
        {
            //new sort
            ShipToModel::where('id','!=',$data->id)->increment('sort');
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
        $data = ShipToModel::find($id);
        return view("$this->prefix.pages.$this->folder.index",[
            'css'=> [
                'back-end/css/table-responsive.css',
                'back-end/css/validate.css',
                'back-end/css/select2.min.css'
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ["src"=>'back-end/js/sweetalert2.all.min.js'],
                ["src"=>'back-end/js/select2.min.js'],
                ['src'=>"back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"back-end/build/$this->folder.js"],
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
        $data = ShipToModel::find($id);
        $data->client = $request->client;
        $data->name = $request->name;
        $data->code = $request->code;
        $data->tax_number = $request->tax_number;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->airport = $request->airport;
        $data->notify_name = $request->notify_name;
        $data->notify_tax_number = $request->notify_tax_number;
        $data->notify_email = $request->notify_email;
        $data->notify_phone = $request->notify_phone;
        $data->notify_address = $request->address;
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
        $datas = ShipToModel::find($id);
        if(@$datas)
        {
            foreach($datas as $data)
            {
                //new sort
                ShipToModel::where('sort','>',$data->sort)->decrement('sort', 1);
                $query = ShipToModel::destroy($data->id);
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
        $get = ShipToModel::find($request->id);
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

        $get = ShipToModel::find($request->id);
        if($from!="" && $to !="")
        {
            if($from > $to){
                ShipToModel::whereBetween('sort', [$to, $from])->whereNotIn("id",[$get->id])->increment('sort', 1);
            }else{
                ShipToModel::whereBetween('sort', [$from, $to])->whereNotIn("id",[$get->id])->decrement('sort', 1);
            }
            $query = ShipToModel::where('id',$get->id)->update(['sort'=>$to]);
            return response()->json($query);
        }
        return response()->json(false);
        
    }
}
