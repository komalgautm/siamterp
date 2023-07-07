<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\FreightModel;
use App\AirportModel;
use App\AirlineModel;
use App\VendorModel;
use App\CurrencyModel;

class Freight extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'freight';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = FreightModel::select('freight.*')->leftJoin('vendors as v','freight.vendor','=','v.id')->orderBy('freight.created','desc')
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
                ["src"=>'/back-end/js/select2.min.js'],
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
            'css'=> [
                'public/back-end/css/table-responsive.css',
                'public/back-end/css/select2.min.css'
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'/back-end/js/select2.min.js'],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"/back-end/build/$this->folder.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'add',
            'segment' => $this->segment,
            'vendors' => VendorModel::where('status','on')->get(),
            'airports' => AirportModel::where('status','on')->get(),
            'airlines' => AirlineModel::where('status','on')->get(),
            'currencys' => CurrencyModel::where('status','on')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $freight = new FreightModel;
        $freight->vendor = $request->vendor;
        $freight->destination = $request->destination;
        $freight->airline = $request->airline;
        $freight->currency = $request->currency;
        $freight->rate_45 = $request->rate_45;
        $freight->rate_100 = $request->rate_100;
        $freight->rate_250 = $request->rate_250;
        $freight->rate_500 = $request->rate_500;
        $freight->rate_1000 = $request->rate_1000;
        $freight->rate_2000 = $request->rate_2000;
        $freight->nego_rate_45 = $request->nego_rate_45;
        $freight->nego_rate_100 = $request->nego_rate_100;
        $freight->nego_rate_250 = $request->nego_rate_250;
        $freight->nego_rate_500 = $request->nego_rate_500;
        $freight->nego_rate_1000 = $request->nego_rate_1000;
        $freight->nego_rate_2000 = $request->nego_rate_2000;
        $freight->status = 'on';
        $freight->sort = 1;
        $freight->created = date('Y-m-d H:i:s');
        $freight->updated = date('Y-m-d H:i:s');
        if($freight->save())
        {
            //new sort
            FreightModel::where('id','!=',$freight->id)->increment('sort');
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
        $data = FreightModel::find($id);
        return view("$this->prefix.pages.$this->folder.index",[
            'css'=> [
                'public/back-end/css/table-responsive.css',
                'public/back-end/css/select2.min.css'
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'/back-end/js/select2.min.js'],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"/back-end/build/$this->folder.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'edit',
            'segment' => $this->segment,
            'row' => $data,
            'vendors' => VendorModel::where('status','on')->get(),
            'airports' => AirportModel::where('status','on')->get(),
            'airlines' => AirlineModel::where('status','on')->get(),
            'currencys' => CurrencyModel::where('status','on')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $freight = FreightModel::find($id);
        $freight->vendor = $request->vendor;
        $freight->destination = $request->destination;
        $freight->airline = $request->airline;
        $freight->currency = $request->currency;
        $freight->rate_45 = $request->rate_45;
        $freight->rate_100 = $request->rate_100;
        $freight->rate_250 = $request->rate_250;
        $freight->rate_500 = $request->rate_500;
        $freight->rate_1000 = $request->rate_1000;
        $freight->rate_2000 = $request->rate_2000;
        $freight->nego_rate_45 = $request->nego_rate_45;
        $freight->nego_rate_100 = $request->nego_rate_100;
        $freight->nego_rate_250 = $request->nego_rate_250;
        $freight->nego_rate_500 = $request->nego_rate_500;
        $freight->nego_rate_1000 = $request->nego_rate_1000;
        $freight->nego_rate_2000 = $request->nego_rate_2000;
        $freight->updated = date('Y-m-d H:i:s');
        if($freight->save())
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function destroy(Request $request)
    {
        $id = explode(',',$request->id);
        $datas = FreightModel::find($id);
        if(@$datas)
        {
            foreach($datas as $data)
            {
                //new sort
                FreightModel::where('sort','>',$data->sort)->decrement('sort', 1);
                $query = FreightModel::destroy($data->id);
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
        $get = FreightModel::find($request->id);
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

        $get = FreightModel::find($request->id);
        if($from!="" && $to !="")
        {
            if($from > $to){
                FreightModel::whereBetween('sort', [$to, $from])->whereNotIn("id",[$get->id])->increment('sort', 1);
            }else{
                FreightModel::whereBetween('sort', [$from, $to])->whereNotIn("id",[$get->id])->decrement('sort', 1);
            }
            $query = FreightModel::where('id',$get->id)->update(['sort'=>$to]);
            return response()->json($query);
        }
        return response()->json(false);
        
    }
}
