<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImportModel;
use App\POModel;
use App\ReceiveModel;
use App\TransectionModel;

class Receive extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'receive';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = DB::table('imports as im')->leftJoin('items as it' , 'item', '=', 'it.id')
        ->leftJoin('unit_count as unit' , 'unit_count', '=', 'unit.id')
        ->leftJoin('pos as po', 'po', '=', 'po.id')
        ->select('im.*','it.name_th as name_th_item','it.name_en as name_en_item','unit.name_th as name_th_unit','unit.name_en as name_en_unit','po.status','po.delivery_date')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('code','like',"%{$keyword}%");
                // ->orWhere('code','like',"%{$keyword}%");
            })->where('po.status','delivery')->where('im.confirm','no')->orderBy('id','desc');
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

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function getItem(Request $request)
    {
        $id = $request->id;
        $data = DB::table('imports as im')->leftJoin('items as it' , 'item', '=', 'it.id')
        ->leftJoin('unit_count as unit' , 'unit_count', '=', 'unit.id')
        ->leftJoin('pos as po' , 'po', '=', 'po.id')
        ->select('im.*','it.name_th as name_th_item','it.name_en as name_en_item','unit.name_th as name_th_unit','unit.name_en as name_en_unit','po.id as po_id')
        ->where('im.id',$id)->first();
    $qty=number_format($data->quantity,2);
        $text['item'] = "<span><b>Name :</b> $data->name_th_item / $data->name_en_item</span> <br>
        <span><b>Unit :</b> $data->name_th_unit / $data->name_en_unit</span> <br>
        <span><b>Quantity :</b> $qty</span> <br>
        <span><b>Number of Crates :</b> $data->crate</span>";

        $text['po_id'] = "$data->po_id";
        $text['qty'] = "$data->quantity";
        $text['crate'] = "$data->crate";
        return $text;
    }

    public function getBoxAndPack(Request $request)
    {
        $id = $request->id;
        $data = DB::table('imports as im')->leftJoin('items as it' , 'item', '=', 'it.id')
        ->leftJoin('unit_count as unit' , 'unit_count', '=', 'unit.id')
        ->leftJoin('pos as po' , 'im.po', '=', 'po.id')
        ->select('im.*','it.name_th as name_th_item','it.name_en as name_en_item','unit.name_th as name_th_unit','unit.name_en as name_en_unit','po.id as po_id')
        ->where('im.id',$id)->first();
        $qty=number_format($data->quantity,2);
        $text['item'] = "<span><b>Name :</b> $data->name_th_item</span> <br>
        <span><b>Unit :</b> $data->name_th_unit / $data->name_en_unit</span> <br>
        <span><b>Quantity :</b> $qty</span>";
        
        $text['po_id'] = "$data->po_id";
        // $text['qty'] = "$data->quantity";
        // $text['crate'] = "$data->crate";
        return $text;
    }

    public function confirm(Request $request)
    {

        $count = DB::table('imports')->where(['po'=>$request->po_id,'confirm'=>'no'])->count();
        if($count == 1){
            $po = POModel::find($request->po_id);
            $po->status = 'receive';
            $po->receive_date = date('Y-m-d H:i:s');
            $po->save();
        }

        $imp = ImportModel::find($request->id);
        $imp->confirm = 'yes';
        $imp->confirm_by = $request->user_id;
        $imp->save();

        $receiving_cost=$this->getReceivingCost($imp->barcode);

        $num_qty= $request->qty;
       
        $transactionValue= $num_qty * $receiving_cost;

        $data = new ReceiveModel;
        $data->po = $imp->po;
        $data->import = $imp->id;
        $data->code = $imp->barcode;
        $data->type = $imp->type;
        $data->item = $imp->item;
        $data->unit = $imp->unit_count;
        $data->receiving_cost = $receiving_cost;
        $data->transactionValue = $transactionValue;
        $data->num_qty = $request->qty;
        $data->transection_type = 'buy';
        $data->receive_date = date('Y-m-d H:i:s');
        $data->balance = $request->qty;
        $data->cal_balance = $request->qty;
        $data->save();

        $tran = new TransectionModel;
        $tran->transactionValue = $transactionValue;
        $tran->transection_type = 'buy';
        $tran->transection_menu = 'receive';
        $tran->ref_id = $data->id;
        $tran->item_id = $imp->item;
        $tran->code = $imp->barcode;
        $tran->type = $imp->type;
        $tran->unit = $imp->unit_count;
        $tran->receiving_cost = $receiving_cost;
        $tran->qty = $request->qty;
        $tran->good_qty = $request->qty;
        $tran->waste_qty = 0;
        $tran->transection_date = date('Y-m-d H:i:s');
        if($tran->save())
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

     function getReceivingCost($barcode,$unit='PC',$w=1)
    {
        $sql=DB::table("imports")->select(['total_price','price'])->where("barcode",$barcode)->first();

        $receiving_cost=$sql->price;;
        if(strtolower($unit)=='box'||strtolower($unit)=='กล่อง')
        {
            $receiving_cost=($sql->total_price/$w);
        }


        
        return $receiving_cost;
    }

    public function confirmKG(Request $request)
    {
    
        $count = DB::table('imports')->where(['po'=>$request->po_idKG,'confirm'=>'no'])->count();
        if($count == 1){
            $po = POModel::find($request->po_idKG);
            $po->status = 'receive';
            $po->receive_date = date('Y-m-d H:i:s');
            $po->save();
        }

        $imp = ImportModel::find($request->id);
        $imp->confirm = 'yes';
        $imp->confirm_by = $request->user_id;
        $imp->save();

            $num_qty=$imp->quantity;
        $receiving_cost=$this->getReceivingCost($imp->barcode,$request->checkUnit,$request->net_weightKG);
        $isBox="no";
         if(strtolower($request->checkUnit)=='box')
        {
            $isBox="yes";
           $num_qty=$request->net_weightKG;
        }

        

      
       
        $transactionValue= $num_qty * $receiving_cost;

        
        $data = new ReceiveModel;
        $data->isBox = $isBox;
        $data->po = $imp->po;
        $data->import = $imp->id;
        $data->code = $imp->barcode;
        $data->type = $imp->type;
        $data->item = $imp->item;
        $data->receiving_cost = $receiving_cost;
        $data->transactionValue = $transactionValue;
        $data->unit = 2;
        $data->num_crate = $request->crateKG;
        $data->num_qty = $imp->quantity;
        $data->total_weight = $request->net_weightKG;
        $data->average_weight = 1;
        $data->transection_type = 'buy';
        $data->sorting = 'no';
        $data->receive_date = date('Y-m-d H:i:s');
        $data->save();

        $tran = new TransectionModel;
        $tran->transection_type = 'buy';
        $tran->transection_menu = 'receive';
        $tran->ref_id = $data->id;
        $tran->item_id = $imp->item;
        $tran->code = $imp->barcode;
        $tran->type = $imp->type;
        $tran->receiving_cost = $receiving_cost;
        $tran->transactionValue = $transactionValue;
        $tran->unit = 2;
        $tran->qty = $request->net_weightKG;
        if($isBox=='no')
        {
          $tran->qty = $imp->quantity;  
        }
        
        $tran->good_qty = $request->net_weightKG;
        $tran->waste_qty = 0;
        $tran->transection_date = date('Y-m-d H:i:s');

        if($tran->save())
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function confirmPC(Request $request)
    {
        $count = DB::table('imports')->where(['po'=>$request->po_idPC,'confirm'=>'no'])->count();
        if($count == 1){
            $po = POModel::find($request->po_idPC);
            $po->status = 'receive';
            $po->receive_date = date('Y-m-d H:i:s');
            $po->save();
        }



        $imp = ImportModel::find($request->id);
        $imp->confirm = 'yes';
        $imp->confirm_by = $request->user_id;
        $imp->save();

        $receiving_cost=$this->getReceivingCost($imp->barcode);

        $num_qty=$imp->quantity;
       
        $transactionValue= $num_qty * $receiving_cost;

        $data = new ReceiveModel;
        $data->po = $imp->po;
        $data->import = $imp->id;
        $data->code = $imp->barcode;
        $data->type = $imp->type;
        $data->item = $imp->item;
        $data->unit = $imp->unit_count;
        $data->receiving_cost = $receiving_cost;
        $data->transactionValue = $transactionValue;
        $data->num_qty = $imp->quantity;
        $data->num_crate = $request->cratePC;
        $data->total_weight = $request->net_weightPC;
        $data->average_weight = $request->averagePC;
        $data->transection_type = 'buy';
        $data->sorting = 'no';
        $data->receive_date = date('Y-m-d H:i:s');
        $data->save();

        $tran = new TransectionModel;
        $tran->transection_type = 'buy';
        $tran->transection_menu = 'receive';
        $tran->ref_id = $data->id;
        $tran->item_id = $imp->item;
        $tran->code = $imp->barcode;
        $tran->type = $imp->type;
        $tran->unit = $imp->unit_count;
        $tran->receiving_cost = $receiving_cost;
        $tran->transactionValue = $transactionValue;
        $tran->qty = $imp->quantity;
        $tran->good_qty = $imp->quantity;
        $tran->waste_qty = 0;
        $tran->transection_date = date('Y-m-d H:i:s');
        if($tran->save())
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }
}
