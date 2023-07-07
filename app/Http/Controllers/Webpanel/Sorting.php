<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ReceiveModel;
use App\SortingModel;
use App\TransectionModel;

class Sorting extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'sorting';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = DB::table('receiving as re')->leftJoin('items as it' , 'item', '=', 'it.id')
        ->leftJoin('unit_count as unit' , 'unit', '=', 'unit.id')
        ->leftJoin('imports as im' , 'import', '=', 'im.id')
        ->leftJoin('pos as po', 're.po', '=', 'po.id')
        ->select('re.*','it.name_th as name_th_item','it.name_en as name_en_item','unit.name_th as name_th_unit','unit.name_en as name_en_unit','im.confirm')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('im.barcode','like',"%{$keyword}%");
                // ->orWhere('code','like',"%{$keyword}%");
            })->where('im.confirm','yes')->where('re.sorting','no')->orderBy('id','desc');
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
        $data = DB::table('receiving as re')->leftJoin('items as it' , 'item', '=', 'it.id')
        ->leftJoin('unit_count as unit' , 'unit', '=', 'unit.id')
        ->leftJoin('imports as im' , 're.import', '=', 'im.id')
        ->select('re.*','it.name_th as name_th_item','it.name_en as name_en_item','unit.name_th as name_th_unit','unit.name_en as name_en_unit','im.total_price')
        ->where('re.id',$id)->first();

        $text['item'] = "<span><b>Name :</b> $data->name_th_item / $data->name_en_item</span> <br>
        <span><b>Unit :</b> $data->name_th_unit / $data->name_en_unit</span> <br>
        <span><b>Quantity :</b> $data->num_qty</span> <br>
        <span><b>Net Weight :</b> $data->total_weight</span> <br>
        <span><b>Number of Crates :</b> $data->num_crate</span>";
        
        $text['qty'] = "$data->num_qty";
        $text['total_weight'] = "$data->total_weight";
        $text['crate'] = "$data->num_crate";
        $text['total_price'] = "$data->total_price";
        return $text;
    }

     function getReceivingCost($barcode,$unit='PC',$w=1)
    {
        $sql=DB::table("imports")->select(['total_price','price'])->where("barcode",$barcode)->first();

        $receiving_cost=$sql->price;;
        if(strtolower($unit)=='box'||strtolower($unit)=='กล่อง'||strtolower($unit)=='yes')
        {
            $receiving_cost=($sql->total_price/$w);
        }






        return $receiving_cost;
    }

    public function sortingKG(Request $request)
    {
        $re = ReceiveModel::find($request->id);


        $re->sorting = 'yes';
        $re->sorting_by = $request->user_id;

        $re->save();
        $receiving_cost=$this->getReceivingCost($re->code,$re->isBox,$re->total_weight);
        $data = new SortingModel;
        $data->receive = $re->id;
        $data->isBox = $re->isBox;
        $data->code = $re->code;
        $data->type = $re->type;
        $data->item = $re->item;
        $data->unit = $re->unit;
        $data->blue_crate = $request->crateKG;
        $data->num_qty = $request->good_weightKG;
        $data->balance = $request->good_weightKG;
        $data->total_price = $request->total_priceKG;
        $data->waste_qty = $request->wastage_weightKG;
        $data->transection_type = 'waste';
        $data->avg_weight = $re->average_weight;
        $data->note = $request->noteKG;
        $data->sorting_date = date('Y-m-d H:i:s');
        $data->packing = 'no';
        $data->save();

      
        $isBox="no";
         if(strtolower($re->isBox)=='box')
        {
            $isBox="yes";
        }

         $num_qty=($request->wastage_weightKG*-1);
       
        $transactionValue= $num_qty * $receiving_cost;

        $tran = new TransectionModel;
        $tran->transection_type = 'waste';
        $tran->transection_menu = 'sorting';
        $tran->ref_id = $data->id;
        $tran->item_id = $re->item;
        $tran->code = $re->code;
        $tran->type = $re->type;
        $tran->unit = $re->unit;
        $tran->qty = round($request->wasteKG);
        $tran->good_qty = round($data->num_qty);
        $tran->waste_qty = round($request->wastage_weightKG*-1);
        $tran->receiving_cost = $receiving_cost;
        $tran->transactionValue = $transactionValue;
        $tran->transection_date = date('Y-m-d H:i:s');

        if($tran->save())
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function sortingPC(Request $request)
    {
        $re = ReceiveModel::find($request->id);
        $re->sorting = 'yes';
        $re->sorting_by = $request->user_id;
        $re->save();

        $data = new SortingModel;
        $data->receive = $re->id;
        $data->code = $re->code;
        $data->type = $re->type;
        $data->item = $re->item;
        $data->unit = $re->unit;
        $data->num_qty = $request->good_weightPC;
        $data->balance = $request->good_weightPC;
        $data->blue_crate = $request->cratePC;
        $data->total_price = $request->total_pricePC;
        $data->waste_qty = $request->wastage_weightPC;
        $data->transection_type = 'waste';
        $data->avg_weight = $re->average_weight;
        $data->note = $request->notePC;
        $data->sorting_date = date('Y-m-d H:i:s');
        $data->packing = 'no';
        $data->save();

         $receiving_cost=$this->getReceivingCost($re->code,$re->unit,$request->good_weightPC);
        $isBox="no";
         if(strtolower($request->checkUnit)=='box')
        {
            $isBox="yes";
        }

         $num_qty=($request->wastage_weightPC*-1);
       
        $transactionValue= $num_qty * $receiving_cost;

        $tran = new TransectionModel;
        $tran->transection_type = 'waste';
        $tran->transection_menu = 'sorting';
        $tran->ref_id = $data->id;
        $tran->item_id = $re->item;
        $tran->code = $re->code;
        $tran->type = $re->type;
        $tran->unit = $re->unit;
        $tran->qty = $request->wastePC;
        $tran->good_qty = $data->num_qty;
        $tran->waste_qty = ($request->wastage_weightPC*-1);
        $tran->transactionValue = $transactionValue; // need to remove
        $tran->receiving_cost = $receiving_cost;
        $tran->transection_date = date('Y-m-d H:i:s');
        if($tran->save())
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }
}
