<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PackingModel;
use App\PackingDetailModel;
use App\ItemModel;
use App\UnitCountModel;
use App\ProductModel;
use App\SortingModel;
use App\TransectionModel;
use App\OrderModel;

class Packing extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'packing';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = PackingDetailModel::select('packing_detail.*','units.name_th as unit_th','units.name_en as unit_en','sort.code','sort.avg_weight','packing_detail.net_weight','pro.code as ean_code','pro.name','pack.id as pack_id','pack.ean_cost','pack.wastage_percent','pack.cost_asl','pack.item','pack.unit as units','sort.total_price as cost_sort')
        ->leftJoin('packing as pack','pack.id','=','packing_detail.packing')
        ->leftJoin('products as pro','packing_detail.product','=','pro.id')
        ->leftJoin('sorting as sort','pack.sorting_id','=','sort.id')
       // ->leftJoin('unit_count as units','pack.unit','=','units.id')
        ->leftJoin('unit_count as units','packing_detail.ean_unit','=','units.id')
        ->where('packing_detail.balance', '!=' , 0)->where('pack.status', '!=' , 'off')
        ->orderBy('created','desc')
        ->when($request->keyword,function($find)use($keyword){
            $find->where('pro.name','like',"%{$keyword}%")->where('packing_detail.balance', '!=' , 0)->where('pack.status', '!=' , 'off')
            ->orWhere('sort.code','like',"%{$keyword}%")->where('packing_detail.balance', '!=' , 0)->where('pack.status', '!=' , 'off')
            ->orWhere('pro.code','like',"%{$keyword}%")->where('packing_detail.balance', '!=' , 0)->where('pack.status', '!=' , 'off');
        });

        if($view=='all'){
            $rows = $query->get();
        }else{
            $view = ($request->view)? $view : 10;
            $rows = $query->paginate($view);
            $rows->appends(['view' => $request->view,'keyword' => $keyword,'page' => $request->page]);
        }
        //      echo"<pre>";
        // print_r(json_decode(json_encode($rows)));
        // die;
        return view("$this->prefix.pages.$this->folder.index",[
            'css'=> [
                'public/back-end/css/table-responsive.css',
                'public/back-end/css/select2.min.css'
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'public/back-end/js/select2.min.js'],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/$this->folder.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'index',
            'segment' => "$this->segment",
            'rows' => $rows,
        ]);
    }

    public function create()
    {

        $itm="SELECT count(s.item) as exist from sorting s where s.item=items.id";
        return view("$this->prefix.pages.$this->folder.index",[
            'css'=> [
                'public/back-end/css/table-responsive.css',
                'public/back-end/css/select2.min.css'
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'public/back-end/js/select2.min.js'],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/$this->folder.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'add',
            'segment' => $this->segment,
            'items' => ItemModel::select('items.*')->selectSub($itm,'exist')->where('type','item')->having('exist','>',0)->get(),
			'wage'=>DB::table('settings')->first()->wages,
             'unit' => UnitCountModel::select('id','name_th as unit_th','name_en as unit_en')->where('status','on')->whereIn('id',[1,2])->get(),
        ]);
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

    public function store(Request $request)
    {
        $pack = new PackingModel;
        $pack->item = $request->item;
        $pack->unit = $request->unit;
        $pack->sorting_id = $request->sorting_id;
        $pack->sorting_qty = $request->balance;
        $pack->cost_asl = $request->cost_asl;
        $pack->po_price = $request->po_price;
        $pack->qty = $request->qty;
        $pack->wastage_weight = $request->wastage_weight;
        $pack->wastage = $request->wastage;
        //$pack->wastage_percent = 1-floatval($request->wastage);
        $pack->wastage_percent = floatval($request->wastage);
        $pack->cost = $request->cost;
        $pack->ean_cost = $request->ean_cost;
        $pack->number_staff = $request->number_staff;
        $pack->start = $request->start;
        $pack->finish = $request->finish;
        $pack->wages = $request->wages;
        $pack->packing_date = date('Y-m-d');
        $pack->created = date('Y-m-d H:i:s');
        $pack->updated = date('Y-m-d H:i:s');
        $pack->save();

        for($i=0; $i<count($request->ean); $i++)
        {
            // $de = new PackingDetailModel;
            // $de->packing = $pack->id;
            // $de->product = $request->ean[$i];
            // $de->number_pack = $request->quantity[$i];
            // $de->product_weight = $request->weight[$i];
            // $de->cost_packaging = $request->cost_packaging[$i];
            // $de->qty_packaging = $request->qty_packaging[$i];
            // $de->wrap_cost = $request->wrap_cost[$i];
            // $de->plus_cost = $request->plus_cost[$i];
            // $de->balance = $request->quantity[$i];
            // $de->cal_balance = $request->quantity[$i];

            // $de->created = date('Y-m-d H:i:s');
            // $de->updated = date('Y-m-d H:i:s');
            // $de->save();

              

             $de = new PackingDetailModel;
            $de->packing = $pack->id;
            $de->product = $request->ean[$i];
            $de->number_pack = $request->quantity[$i];
            $de->product_weight = $request->weight[$i];
            $de->cost_packaging = $request->cost_packaging[$i];
            $de->qty_packaging = $request->quantity[$i];
            $de->number_pack = $request->quantity[$i];
            $de->wrap_cost = $request->wrap_cost[$i];
            $de->plus_cost = $request->plus_cost[$i];
            $de->balance = $request->quantity[$i];
            $de->cal_balance = $request->quantity[$i];
            $de->ean_unit = $request->ean_unit[$i];
            $de->cost_ean = $request->cost_ean[$i];   

            $de->ean_uc = $request->ean_uc[$i];
            $de->ean_uw = $request->aslValue[$i];
            $de->net_weight = $request->netWeight[$i];

            $de->created = date('Y-m-d H:i:s');
            $de->updated = date('Y-m-d H:i:s');
            $de->save();
            
        }

        $sort = SortingModel::find($request->sorting_id);
        $sort->balance = floatval($request->balance)-floatval($request->qty);
        if(floatval($request->balance)-floatval($request->qty) == 0){
        $sort->packing = 'yes';
        $sort->packing_date = date('Y-m-d H:i:s');
        }
        $sort->save();

        //   $isBox="no";
        //  if(strtolower($re->isBox)=='box')
        // {
        //     $isBox="yes";
        // }


         $receiving_cost=$this->getReceivingCost($sort->code,$sort->isBox,($sort->num_qty+$sort->waste_qty));

        $num_qty=($request->wastage_weight*-1);
       
        $transactionValue= $num_qty*$receiving_cost;

        $tran = new TransectionModel;
        $tran->receiving_cost = $receiving_cost;
        $tran->transactionValue = $transactionValue;
        
        $tran->transection_type = 'waste';
        $tran->transection_menu = 'packed';
        $tran->ref_id = $pack->id;
        $tran->item_id = $sort->item;
        $tran->code = $sort->code;
        $tran->type = $sort->type;
        $tran->unit = $sort->unit;
        $tran->qty = round(floatval($request->qty)-floatval($request->wastage_weight)+-floatval($request->qty));
        $tran->good_qty = round(floatval($request->qty)-floatval($request->wastage_weight));
        $tran->waste_qty = round($request->wastage_weight*-1);
        $tran->transection_date = date('Y-m-d H:i:s');
        if($tran->save())
        {
            return view("$this->prefix.alert.sweet.success",['url'=>url("$this->folder")]);
        }else{
            return view("$this->prefix.alert.sweet.error",['url'=>$request->fullUrl()]);
        }
    }

    public function show($id)
    {
        $data = PackingModel::select('packing.*','it.name_th','it.name_en','unit.name_th as unit_th','unit.name_en as unit_en','sort.code','sort.blue_crate','sort.num_qty')
        ->leftJoin('sorting as sort','packing.sorting_id','=','sort.id')
        ->leftJoin('unit_count as unit','sort.unit','=','unit.id')
        ->leftJoin('items as it','sort.item','=','it.id')
        ->where('packing.id',$id)->first();
        return view("$this->prefix.pages.$this->folder.index",[
            'css'=> [
                'public/back-end/css/table-responsive.css',
                'public/back-end/css/select2.min.css'
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'public/back-end/js/select2.min.js'],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/$this->folder.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'view',
            'segment' => $this->segment,
            'row' => $data,
            'details' => PackingDetailModel::leftJoin('products as pro','packing_detail.product','=','pro.id')->where('packing',$id)->get(),
        ]);
    }



    public function edit($id)
    {
        $data = PackingModel::leftJoin('sorting as sort','packing.sorting_id','=','sort.id')
        ->leftJoin('unit_count as unit','sort.unit','=','unit.id')
        ->where('packing.id',$id)->first();
        return view("$this->prefix.pages.$this->folder.index",[
            'css'=> [
                'public/back-end/css/table-responsive.css',
                'public/back-end/css/validate.css',
                'public/back-end/css/select2.min.css',
                // 'public/back-end/bootstrap-select-1.13.0-dev/dist/css/bootstrap-select.css',
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ['src'=>'public/back-end/Inputmask-5.x/dist/jquery.inputmask.min.js'],
                // ["src"=>'public/back-end/bootstrap-select-1.13.0-dev/dist/js/bootstrap-select.js'],
                ["src"=>'public/back-end/js/select2.min.js'],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/$this->folder.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'edit',
            'segment' => $this->segment,
            'row' => $data,
            'items' => ItemModel::where('type','item')->get(),
            'units' => UnitCountModel::where('status','on')->get(),
            'details' => PackingDetailModel::where('packing',$id)->get(),
            'eans' => ProductModel::leftJoin('setups','products.id','=','setups.product')->select('products.id','products.name')->where(['setups.item'=>$data->item,'setups.type'=>'item'])->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function getUnit(Request $request)
    {
        $datas = DB::table('unit_count as units')->where('status','on')->get();
        $text = '<option value="">Select Unit</option>';
        foreach($datas as $data)
        {
            if($data->id==1||$data->id==2)
            $text.='<option value="'.$data->id.'">'.$data->name_th." / ".$data->name_en.'</option>';
        }
        return $text;
    }

    public function getVal(Request $request)
    {
        $item = $request->item;
        $unit = $request->unit;

        $receiving_cost="SELECT receiving_cost from receiving as r where r.code=im.barcode limit 1";
        $isBox="SELECT isBox from receiving as r where r.code=im.barcode limit 1";
        $avg_weightR="SELECT average_weight from receiving as r where r.code=im.barcode limit 1";


        $data = DB::table('sorting as sort')
        ->select('sort.*','im.price','po.receive_date','units.name_th','units.name_en')
        ->leftJoin('imports as im', 'sort.code', '=', 'im.barcode')
        ->leftJoin('pos as po', 'im.po', '=', 'po.id')
        ->leftJoin('unit_count as units', 'sort.unit', '=', 'units.id')
        ->selectSub($receiving_cost,'receiving_cost')
        ->selectSub($isBox,'isBox')
        ->selectSub($avg_weightR,'avg_weightR')
        ->where(['sort.item'=>$item,'sort.unit'=>$unit,'sort.packing'=>'no','sort.status'=>'on'])->orderBy('po.receive_date','asc')->first();
        if($data){
            $text['id'] = $data->id;
            $text['code'] = $data->code;
            $text['qty'] = $data->balance;
            $text['unit'] = $data->name_th." / ".$data->name_en;
            $text['blue_crate'] = $data->blue_crate;
            $text['cost_asl'] = $data->total_price;
            $text['po_price'] = $data->price;
            $text['po_price'] = $data->price;
            $text['avg_weight'] = $data->avg_weight;
            $text['item'] = $data->item;
            $text['isBox'] = $data->isBox;
            $text['total_price'] = $data->total_price;
            $text['receiving_cost'] = $data->receiving_cost;
        }else{
            $text['id'] = "";
            $text['total_price'] = "";
            $text['code'] = "";
            $text['qty'] = "";
            $text['unit'] = "";
            $text['blue_crate'] = "";
            $text['cost_asl'] = "";
            $text['po_price'] = "";
            $text['avg_weight'] = "";
            $text['item'] = "";
            $text['isBox'] = "";
            $text['isBoreceiving_costx'] = "";
        }
        return $text;
    }

    public function getEAN(Request $request)
    {
        $id = $request->id;
        $datas = DB::table('products')->leftJoin('setups','products.id','=','setups.product')->select('products.id','products.name')
        ->where(['setups.item'=>$id,'setups.type'=>'item','products.status'=>'on'])->get();
        $text = '<option value="">Select EAN</option>';
        foreach($datas as $data)
        {
            $text.='<option value="'.$data->id.'">'.$data->name.'</option>';
        }
        return $text;
    }

    public function CalWages(Request $request)
    {
        $start = $request->start;
        $finish = $request->finish;
        $number = $request->number;
        $qty = $request->qty;
		$wages = DB::table("settings")->select('wages')->first();
		print_r($wages); die;
        $cal = ((strtotime($finish) - strtotime($start))/( 60 * 60 ))*$number*60/floatval($qty);
        // (strtotime($strTime2) - strtotime($strTime1))/  ( 60 * 60 ); // 1 Hour
        return $cal;
    }

    public function getWeightEAN(Request $request)
    {
        $id = $request->id;
        $data = ProductModel::find($id);
        $weight = $data->total_qty/1000;
        return $weight;
    }

 public function getCostPack(Request $request)
    {
        // old code start
        //$id = $request->id;
        /*$datas = DB::table('setups as set')
        ->leftJoin('receiving as re','set.item','=','re.item')
        ->leftJoin('imports as im','re.import','=','im.id')
        ->where(['set.type'=>'packaging','set.product'=>$id])
        ->orderBy('re.receive_date','asc')->get();*/
        
         /*$datas = DB::table('setups as set')
        ->leftJoin('receiving as re','set.item','=','re.item')
        ->leftJoin('imports as im','re.import','=','im.id')
        ->where(['set.type'=>'packaging','set.product'=>$id])
        ->orderBy('re.receive_date','desc')->first();
        
        $sum_cost = 0;
        $qty = 0;/*
   


        
     
      
//echo json_encode($datas);
        $qty=$datas->qty; 
        $sum_cost=($datas->price*(int)$datas->qty);

       /* foreach($datas as $key => $data) 
        {
            $qty+=$data->qty;  

            $sum_cost+=($data->price*(int)$data->qty);

           
        }*/
      
        /*$text['sum_cost'] = $sum_cost;
        $text['qty'] = $qty;
     
       return $text;*/
       // old code end
       $id = $request->id;
        $sum_cost1 = 0;
        $qty = 0;
        /*$datas = DB::table('setups as set')
        ->leftJoin('receiving as re','set.item','=','re.item')
        ->leftJoin('imports as im','re.import','=','im.id')
        ->where(['set.type'=>'packaging','set.product'=>$id])
        ->orderBy('re.receive_date','asc')->get();*/
        
        $datas = DB::table('setups as set')
        ->where(['set.type'=>'packaging','set.product'=>$id])
        ->get();
         foreach($datas as $key => $data) 
         {  
             $qty+=$data->qty;  
             $re = DB::table('receiving')->where('item',$data->item)->orderBy('id','desc')->first();
             $im = DB::table('imports')->where('id',$re->import)->first();
              //echo "<pre>"; print_r($re); 
              $sum_cost1+=($im->price*(int)$data->qty);
         }
         
        $sum_cost = number_format($sum_cost1,2);
         
        $text['sum_cost'] = $sum_cost;
        $text['qty'] = $qty;
     
       return $text;
    }
    public function getWrapCost(Request $request)
    {
        $id = $request->id;
        $datas = DB::table('setups as set')
        ->leftJoin('items as it','set.item','=','it.id')
        ->where('set.product',$id)
        ->where('set.type','packaging')->get();
        $sum_wrap = 0;
        $sum_wrap1 = 0;
        $qty = 0;

        foreach($datas as $data){
            $sum_wrap+=$data->wrap_cost;
            $sum_wrap1+=$data->wrap_cost*$data->qty;
            $qty+=$data->qty;

        }
        $text['sum_wrap'] = $sum_wrap;
        $text['sum_wrap1'] = $sum_wrap1;
        $text['qty'] = $qty;
        return $text;
   
    }

    public function destroyEAN(Request $request)
    {
        $data = PackingDetailModel::destroy($request->id);
        if($data){
            return "true";
        }else{
            return "false";
        }
    }

    public function restore(Request $request)
    {
        $id = $request->id;
        $data = PackingModel::find($id);
		
        $packed=PackingDetailModel::where("packing",$id)->get();

        $used="0";
        foreach($packed as $pk)
        {
            $packing_detail_id=$pk->id;
            $isSale= DB::table("pol")->where(["packing_detail_id"=>$packing_detail_id])->count();
            if($isSale>0)
            {

                $used="1";
                break;
            }

        }
		if($used=='1')
		{
			return "false";
		}

		

        

        $sort = SortingModel::where('id',$data->sorting_id)->first();
        $sort->balance = floatval($data->qty)+floatval($sort->balance);
        $sort->status = 'on';
        if($sort->balance!= 0){
            $sort->packing = 'no';
            $sort->packing_date = null;
        }
        $sort->save();
        $tran = TransectionModel::where(['code'=>$sort->code,'transection_type'=>'waste','ref_id'=>$id])->orderBy('transection_date','desc')->first();
        TransectionModel::destroy($tran->id);
        // $tran = new TransectionModel;
        // $tran->transection_type = 'restore';
        // $tran->item_id = $data->item;
        // $tran->code = $sort->code;
        // $tran->type = $sort->type;
        // $tran->unit = $data->unit;
        // $tran->qty = $data->wastage_weight;
        // $tran->good_qty = floatval($data->qty)+floatval($data->wastage_weight);
        // $tran->waste_qty = "";
        // $tran->transection_date = date('Y-m-d H:i:s');
        // $tran->save();

        $detail = PackingDetailModel::where('packing',$id)->get();
        foreach($detail as $de){
            PackingDetailModel::destroy($de->id);
        }

        $pack = PackingModel::destroy($id);
        if($pack)
        {
            return 'true';
        }else{
            return 'false';
        }
    }

    public function getPackVal(Request $request)
    {
        $data = PackingDetailModel::select('packing_detail.*','units.name_th as unit_th','units.name_en as unit_en','sort.code','pro.code as ean_code','pro.name')
        ->leftJoin('packing as pack','pack.id','=','packing_detail.packing')
        ->leftJoin('sorting as sort','pack.sorting_id','=','sort.id')
        ->leftJoin('products as pro','packing_detail.product','=','pro.id')
        ->leftJoin('unit_count as units','sort.unit','=','units.id')
        ->where('packing_detail.id',$request->id)->first();
        $text['name'] = $data->name;
        $text['code'] = $data->code;
        $text['ean'] = $data->ean_code;
        $text['qty'] = $data->balance;
        $text['unit'] = $data->unit_th." / ".$data->unit_en;
        return $text;
    }

    public function waste(Request $request)
    {
        $id = $request->id;
        $data = PackingModel::find($id);
        $data->status = 'off';
        if($data->save()){
            return 'true';
        }else{
            return 'false';
        }
    }


      public function getEANDetails(Request $request)
    {
        $id = $request->id;
         $data = DB::table('setups as set')
        ->leftJoin('items as it','set.item','=','it.id')
        ->where('set.product',$id)
        ->where('set.type','item')->first();
       
        return json_encode($data);
    }


        public function getEANStock(Request $request)
    {
         $id = $request->id;
         $usedQty = $request->usedQty;
   
        $sql= DB::table('setups')->select('item')->where('type','!=','item')->where('product',$id);
        if($sql->count()==0)
        {
             $sql1= DB::table('setups')->select('item')->where('product',$id);
            if($sql1->count()==1)
            {
                return 1;
            }

            return 0;
        }

        $data=$sql->get();

        foreach ($data as $key => $v) 
        {
             $bl= DB::table('receiving')->select('cal_balance')->where('type','!=','item')->where('item',$v->item)->first()->cal_balance;
             if($bl<$usedQty)
             {
                return 0;
                break;
             }
        }

        return 1;

      return $data;
    }


  public function showPack($id)
    {
        $data['pack'] = PackingModel::select('packing.*','it.name_th','it.name_en','unit.name_th as unit_th','unit.name_en as unit_en','sort.code','sort.blue_crate','sort.num_qty')
        ->leftJoin('sorting as sort','packing.sorting_id','=','sort.id')
        ->leftJoin('unit_count as unit','sort.unit','=','unit.id')
        ->leftJoin('items as it','sort.item','=','it.id')
        ->where('packing.id',$id)->first();

           $data['details'] = PackingDetailModel::leftJoin('products as pro','packing_detail.product','=','pro.id')->where('packing',$id)->get();

        return $data;
       
    }




 public function registerWaste(request $request)
    {

     if($request->unit=='2'&&$request->ean_unit!="2")
      {
        $qty=($request->net_weight/1000)*$request->wasteQty;
      }
      else
      {
        $qty=$request->wasteQty;
      }

    $receiving_cost=DB::table("transection")->where(["code"=>$request->code,'transection_type'=>'buy','item_id'=>$request->item])->first()->receiving_cost;
        $tran = new TransectionModel;
       // $receiving_cost=$request->receiving_cost;
        $transactionValue= $receiving_cost*$qty*-1;
        $tran->receiving_cost = $receiving_cost;
        $tran->transactionValue = $transactionValue;
        $tran->transection_type = 'waste';
        $tran->transection_menu = 'damage';
        $tran->ref_id = 0;
        $tran->item_id = $request->item;
        $tran->code = $request->code;
        $tran->type = "item";
        $tran->unit = $request->unit;
        $tran->qty = (floatval($qty)*-1);
        $tran->good_qty = floatval($request->goodQty);
        $tran->waste_qty = $qty*-1;
        $tran->transection_date = date('Y-m-d H:i:s');


        //   echo"<pre>";
        // print_r(json_decode(json_encode($request->all())));
        // print_r(json_decode(json_encode($tran)));
        // die;
     
  
       if($tran->save())
        {
    DB::table("packing_detail")->where("id",$request->pack_detail_id)->update(["balance"=>$request->goodQty]);
    
            return view("$this->prefix.alert.sweet.success",['url'=>url("$this->folder")]);
        }else{
            return view("$this->prefix.alert.sweet.error",['url'=>$request->fullUrl()]);
        }

      

    }



}
