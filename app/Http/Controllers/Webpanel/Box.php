<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ItemModel;

class Box extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'box';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = ItemModel::orderBy('created','desc')->where('type','boxes')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('name_th','like',"%{$keyword}%");
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
        $data = new ItemModel;
        $data->type = 'boxes';
        $data->name_th = $request->name;
        $data->width = $request->width;
        $data->length = $request->length;
        $data->height = $request->height;
        $data->cbm = $request->cbm;
        $data->weight = $request->weight;
        $data->minload = $request->minload;
        $data->box_pallet = $request->box_pallet;
        $data->status = 'on';
        $data->created = date('Y-m-d H:i:s');
        if($data->save()){
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
        $row = ItemModel::find($id);
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
            'row' => $row,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = ItemModel::find($id);
        $data->name_th = $request->name;
        $data->width = $request->width;
        $data->length = $request->length;
        $data->height = $request->height;
        $data->cbm = $request->cbm;
        $data->weight = $request->weight;
        $data->minload = $request->minload;
        $data->box_pallet = $request->box_pallet;
        $data->updated = date('Y-m-d H:i:s');
        if($data->save()){
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function status(Request $request)
    {
        $get = ItemModel::find($request->id);
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

    public function destroy(Request $request)
    {
        $id = explode(',',$request->id);
        $datas = ItemModel::find($id);
        if(@$datas)
        {
            foreach($datas as $data)
            {
                $query = ItemModel::destroy($data->id);
            }
        }
        
        if($query){
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }
}
