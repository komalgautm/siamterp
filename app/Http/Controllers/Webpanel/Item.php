<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ItemModel;

class Item extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'item';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = ItemModel::orderBy('name_en','asc')->where('type','item')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('name_th','like',"%{$keyword}%")
                ->orWhere('name_en','like',"%{$keyword}%")
                ->orWhere('barcode','like',"%{$keyword}%");
            });

        if($view=='all'){
            $rows = $query->get();
        }else{
            $view = ($request->view)? $view : 10 ;
            $rows = $query->paginate($view);
            $rows->appends(['view' => $request->view,'keyword' => $keyword,'page' => $request->page]);
        }
        return view("$this->prefix.pages.item.index",[
            'css'=> ['public/back-end/css/table-responsive.css'],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/item.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => 'item',
            'page' => 'index',
            'segment' => "$this->segment",
            'rows' => $rows
        ]);
    }

    public function create()
    {
        return view("$this->prefix.pages.item.index",[
            'css'=> ['public/back-end/css/table-responsive.css','public/back-end/css/validate.css'],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/item.js"],
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
        $data->name_th = $request->name_th;
        $data->name_en = $request->name_en;
        $data->hs_code = $request->hs_code;
        $data->hs_name = $request->hs_name;
        $data->status = 'on';
        $data->type = 'item';
        $data->sort = 1;
        $data->created = date('Y-m-d H:i:s');
        $data->updated = date('Y-m-d H:i:s');
        if($data->save())
        {
            //gen code
            ItemModel::getBarcode($data->id);
            //new sort
            ItemModel::where('id','!=',$data->id)->increment('sort');
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
            'css' => ['public/back-end/css/table-responsive.css','public/back-end/css/validate.css'],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ['src'=>"public/back-end/tinymce/tinymce.min.js"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ["type"=>"text/javascript","src"=>"public/back-end/build/item.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'edit',
            'segment' => $this->segment,
            'row' => $row
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = ItemModel::find($id);
        $data->name_th = $request->name_th;
        $data->name_en = $request->name_en;
        $data->hs_code = $request->hs_code;
        $data->hs_name = $request->hs_name;
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
        $datas = ItemModel::find($id);
        if(@$datas)
        {
            foreach($datas as $data)
            {
                //new sort
                ItemModel::where('sort','>',$data->sort)->decrement('sort', 1);
                $query = ItemModel::destroy($data->id);
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

    public function dragsort(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $get = ItemModel::find($request->id);
        if($from!="" && $to !="")
        {
            if($from > $to){
                ItemModel::whereBetween('sort', [$to, $from])->whereNotIn("id",[$get->id])->increment('sort', 1);
            }else{
                ItemModel::whereBetween('sort', [$from, $to])->whereNotIn("id",[$get->id])->decrement('sort', 1);
            }
            $query = ItemModel::where('id',$get->id)->update(['sort'=>$to]);
            return response()->json($query);
        }
        return response()->json(false);
        
    }
}
