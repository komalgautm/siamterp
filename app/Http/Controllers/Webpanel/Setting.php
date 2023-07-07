<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\MenuModel;

class Setting extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $data = \App\MenuModel::where('position','main')
        ->when($request->keyword,function($find)use($keyword){
            $find->where('name','like',"%{$keyword}%");
        })->orderBy('sort');
        if($request->view=='all'){
            $rows = $data->get();
        }else{
            $view = ($request->view)? $request->view : 10 ;
            $rows = $data->paginate($view);
            $rows->appends(['view'=>$request->view,'page'=>$request->page,'keywords'=>$request->keyword]);
        }
        return view("$this->prefix.pages.menu.index",[
            'css'=> ['public/back-end/css/table-responsive.css'],        
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>"/back-end/js/table-dragger.min.js"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ["type"=>"text/javascript","src"=>"public/back-end/build/setting.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => 'menu',
            'page' => 'index',
            'segment' => "$this->segment",
            'rows' => $rows
        ]);
    }


    public function create()
    {
        return view("$this->prefix.pages.menu.index",[            
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/setting.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => 'menu',
            'page' => 'add',
            'segment' => "$this->segment",
            'main' => \App\MenuModel::where('position','=','main')->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = new \App\MenuModel;
        $data->position = $request->position;
        $data->_id = $request->_id;
        $data->name = $request->name;
        $data->icon = $request->icon;
        $data->url = $request->url;
        $data->status = 'on';
        $data->created = date('Y-m-d H:i:s');
        if($data->save()){
            return view("$this->prefix.alert.sweet.success",['url'=> url("/menu")]);
        }else{
            return view("$this->prefix.alert.sweet.error",['url'=> url("/menu")]);
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        return view("$this->prefix.pages.menu.index",[
            'css'=> [
                "public/back-end/css/select2.min.css",
                "public/back-end/css/select2-coreui.min.css",
                "public/back-end/css/bootstrap-select.min.css",
                "public/back-end/css/validate.css",
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/setting.js"],
                
            ],
            'prefix' => $this->prefix,
            'folder' => 'menu',
            'page' => 'edit',
            'segment'=> "$this->segment/menu",
            'row' => \App\MenuModel::find($id),
            'main' => \App\MenuModel::where('position','=','main')->get()
        ]);
    }


    public function update(Request $request, $id)
    {
        $data = \App\MenuModel::find($id);
        $data->position = $request->position;
        $data->_id = $request->_id;
        $data->name = $request->name;
        $data->icon = $request->icon;
        $data->url = $request->url;
        // $data->meta_title = $request->meta_title;
        // $data->meta_keywords = $request->meta_keywords;
        // $data->meta_description = $request->meta_description;
        $data->updated = date('Y-m-d H:i:s');
        if($data->save()){
            return view("$this->prefix.alert.sweet.success",['url'=> url("/menu")]);
        }else{
            return view("$this->prefix.alert.sweet.error",['url'=> url("/menu")]);
        }
    }
    public function dragsort(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $data = MenuModel::find($request->id);

        if($from!="" && $to !="")
        {
            if($from > $to){
                MenuModel::whereBetween('sort', [$to, $from])->whereNotIn('id',[$data->id])->increment('sort');
            }else{
                MenuModel::whereBetween('sort', [$from, $to])->whereNotIn('id',[$data->id])->decrement('sort');
            }
            $data->sort = $to;
            if($data->save()){
                return response()->json(true);
            }else{
                return response()->json(false);
            }
        }
        return response()->json(false);
    }

    public function status($id=null)
    {
        $data = \App\MenuModel::find($id);
        $data->status = ($data->status=='off')?'on':'off';
        // if($data->save()){ return response()->json(true); }else{ return response()->json(false); }\
        if($data->save()){ return response()->json(true); }else{ return response()->json(false); }
    }

    public function destroy(Request $request, $id=null)
    {
        $id = explode(',',$request->id);
        $datas = \App\MenuModel::find($id);
        foreach($datas as $data)
        {
            //new sort
            // MenuModel::where('sort','>',$data->sort)->decrement('sort', 1);
            $query = MenuModel::destroy($data->id);
        }
        if($query){
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }
}
