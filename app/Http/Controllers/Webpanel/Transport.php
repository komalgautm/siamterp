<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\TransportModel;

class Transport extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'transport';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = TransportModel::select('transport.*','v.name')->leftJoin('vendors as v','transport.vendor','=','v.id')->orderBy('transport.created','desc')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('v.name','like',"%{$keyword}%");
                // ->orWhere('city','like',"%{$keyword}%")
                // ->orWhere('airport_code','like',"%{$keyword}%");
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
                'public/back-end/css/table-responsive.css','public/back-end/css/validate.css',
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
        $data = new TransportModel;
        $data->vendor = $request->vendor;
        $data->small_max_cbm = $request->small_max_cbm;
        $data->small_max_weight = $request->small_max_weight;
        $data->small_pallet = $request->small_pallet;
        $data->small_price = $request->small_price;
        $data->medium_max_cbm = $request->medium_max_cbm;
        $data->medium_max_weight = $request->medium_max_weight;
        $data->medium_pallet = $request->medium_pallet;
        $data->medium_price = $request->medium_price;
        $data->large_max_cbm = $request->large_max_cbm;
        $data->large_max_weight = $request->large_max_weight;
        $data->large_pallet = $request->large_pallet;
        $data->large_price = $request->large_price;
        $data->jumbo_max_cbm = $request->jumbo_max_cbm;
        $data->jumbo_max_weight = $request->jumbo_max_weight;
        $data->jumbo_pallet = $request->jumbo_pallet;
        $data->jumbo_price = $request->jumbo_price;
        $data->status = 'on';
        $data->created = date('Y-m-d H:i:s');
        $data->updated = date('Y-m-d H:i:s');
        if($data->save())
        {
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
        $data = TransportModel::find($id);
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
            'page' => 'edit',
            'segment' => $this->segment,
            'row' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = TransportModel::find($id);
        $data->vendor = $request->vendor;
        $data->small_max_cbm = $request->small_max_cbm;
        $data->small_max_weight = $request->small_max_weight;
        $data->small_pallet = $request->small_pallet;
        $data->small_price = $request->small_price;
        $data->medium_max_cbm = $request->medium_max_cbm;
        $data->medium_max_weight = $request->medium_max_weight;
        $data->medium_pallet = $request->medium_pallet;
        $data->medium_price = $request->medium_price;
        $data->large_max_cbm = $request->large_max_cbm;
        $data->large_max_weight = $request->large_max_weight;
        $data->large_pallet = $request->large_pallet;
        $data->large_price = $request->large_price;
        $data->jumbo_max_cbm = $request->jumbo_max_cbm;
        $data->jumbo_max_weight = $request->jumbo_max_weight;
        $data->jumbo_pallet = $request->jumbo_pallet;
        $data->jumbo_price = $request->jumbo_price;
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
        $datas = TransportModel::find($id);
        if(@$datas)
        {
            foreach($datas as $data)
            {
                //new sort
                TransportModel::where('sort','>',$data->sort)->decrement('sort', 1);
                $query = TransportModel::destroy($data->id);
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
        $get = TransportModel::find($request->id);
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

        $get = TransportModel::find($request->id);
        if($from!="" && $to !="")
        {
            if($from > $to){
                TransportModel::whereBetween('sort', [$to, $from])->whereNotIn("id",[$get->id])->increment('sort', 1);
            }else{
                TransportModel::whereBetween('sort', [$from, $to])->whereNotIn("id",[$get->id])->decrement('sort', 1);
            }
            $query = TransportModel::where('id',$get->id)->update(['sort'=>$to]);
            return response()->json($query);
        }
        return response()->json(false);
        
    }
}
