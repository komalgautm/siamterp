<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\TransectionModel as Model;


class Inventory extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'inventory';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $vl="SELECT sum(tt.transactionValue) as vl from transection as tt WHERE tt.item_id=tran.item_id and tt.unit=tran.unit and tt.transection_type='buy'";
        $vl1="SELECT sum(tt.transactionValue) as vl1 from transection as tt WHERE tt.item_id=tran.item_id and tt.unit=tran.unit and tt.transection_type='waste'";

        $totalTr="SELECT sum(tt.transactionValue) as vl1 from transection as tt WHERE tt.item_id=tran.item_id and tt.unit=tran.unit";
        $query = DB::table('items as itm')->select('itm.*','units.name_th as unit_th','units.name_en as unit_en','units.id as unit_id','tran.transection_date','tran.transactionValue',)
        ->leftJoin('transection as tran','itm.id', '=', 'tran.item_id')
        ->leftJoin('unit_count as units', 'tran.unit', '=', 'units.id')
        ->selectSub($vl,'vl')
        ->selectSub($vl1,'vl1')
        ->selectSub($totalTr,'totalTr')
        ->groupBy('itm.id','units.id')->when($request->keyword,function($find)use($keyword){
                $find->where('itm.name_th','like',"%{$keyword}%")
                ->orWhere('itm.name_en','like',"%{$keyword}%");
            })->orderBy('itm.sort','asc');
        if($view=='all'){
            $rows = $query->get();
        }else{
            $view = ($request->view)? $view : 10 ;
            $rows = $query->paginate($view);
            $rows->appends(['view' => $request->view,'keyword' => $keyword,'page' => $request->page]);
        }

        //  echo"<pre>";print_r($rows);
        // die;
        return view("$this->prefix.pages.$this->folder.index",[
            'css'=> ['public/back-end/css/table-responsive.css'],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/$this->folder.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'index',
            'segment' => $this->segment,
            'rows' => $rows,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id,$unit)
    {
        $data = Model::select('transection.*','it.name_th as name_th_item','it.name_en as name_en_item','unit.name_th as name_th_unit','unit.name_en as name_en_unit')
        ->leftJoin('items as it' , 'transection.item_id', '=', 'it.id')->leftJoin('unit_count as unit' , 'transection.unit', '=', 'unit.id')
        ->where(['item_id'=>$id,'unit'=>$unit])->orderBy('transection.transection_date','asc')->get();

        // echo"<pre>";print_r($data);
        // die;
        return view("$this->prefix.pages.$this->folder.index",[
            'css'=> ['public/back-end/css/table-responsive.css'],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/$this->folder.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'view',
            'segment' => $this->segment,
            'rows' => $data,
        ]);
    }

    public function edit($id)
    {
        //
    }

    public function adjust(Request $request)
    {
        $item_id=$request->item_id;
        $type=$request->type;
        $qty=$request->qty;
        $unit=$request->unit_id;

        $data['item_id']=$item_id;
        $data['type']=$type;
        $data['qty']=$qty;
        $data['unit']=$unit;
        $data['transection_type']="adjustment";
        $data['transection_menu']="inventory";
        $data['transection_date']=date("Y-m-d H:i:s");


        $receiving_cost=DB::table('transection')->where(['item_id'=>$item_id,'unit'=>$unit])->orderBy('transection_date','desc')->first()->receiving_cost;
        $data['receiving_cost']=$receiving_cost;
        $data['transactionValue']=$receiving_cost*$qty;
        if($qty<0)
        {
            $data['waste_qty']=$qty;            
            $data['good_qty']=0;            
        }
        else
        {
              $data['good_qty']=$qty;  
              $data['waste_qty']=0; 
        }

        $in=DB::table('transection')->insertGetId($data);

        return redirect("inventory");

       
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
