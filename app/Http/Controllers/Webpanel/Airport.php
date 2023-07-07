<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\AirportModel;

class Airport extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'airport';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = AirportModel::orderBy('created','desc')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('country','like',"%{$keyword}%")
                ->orWhere('city','like',"%{$keyword}%")
                ->orWhere('airport_code','like',"%{$keyword}%");
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
        $airport = new AirportModel;
        $airport->country = $request->country;
        $airport->city = $request->city;
        $airport->airport_code = $request->airport_code;
        $airport->status = 'on';
        $airport->sort = 1;
        $airport->created = date('Y-m-d H:i:s');
        $airport->updated = date('Y-m-d H:i:s');
        if($airport->save())
        {
            //new sort
            AirportModel::where('id','!=',$airport->id)->increment('sort');
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
        $data = AirportModel::find($id);
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
        $airport = AirportModel::find($id);
        $airport->country = $request->country;
        $airport->city = $request->city;
        $airport->airport_code = $request->airport_code;
        $airport->updated = date('Y-m-d H:i:s');
        if($airport->save())
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }

    }

    public function destroy(Request $request)
    {
        $id = explode(',',$request->id);
        $datas = AirportModel::find($id);
        if(@$datas)
        {
            foreach($datas as $data)
            {
                //new sort
                AirportModel::where('sort','>',$data->sort)->decrement('sort', 1);
                $query = AirportModel::destroy($data->id);
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
        $get = AirportModel::find($request->id);
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

        $get = AirportModel::find($request->id);
        if($from!="" && $to !="")
        {
            if($from > $to){
                AirportModel::whereBetween('sort', [$to, $from])->whereNotIn("id",[$get->id])->increment('sort', 1);
            }else{
                AirportModel::whereBetween('sort', [$from, $to])->whereNotIn("id",[$get->id])->decrement('sort', 1);
            }
            $query = AirportModel::where('id',$get->id)->update(['sort'=>$to]);
            return response()->json($query);
        }
        return response()->json(false);
        
    }
}
