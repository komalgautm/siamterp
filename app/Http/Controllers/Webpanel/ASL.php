<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\SortingModel;
use App\ItemModel;
use App\ProductModel;
use App\PackingModel;
use App\PackingDetailModel;
use App\TransectionModel;
use App\UnitCountModel;

class ASL extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'asl';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = DB::table('sorting as sort')->leftJoin('items as it' , 'item', '=', 'it.id')
        ->leftJoin('unit_count as unit' , 'unit', '=', 'unit.id')
        ->leftJoin('receiving as re' , 'receive', '=', 're.id')
        ->select('sort.*','it.id as item_id','it.name_th as name_th_item','it.name_en as name_en_item','unit.name_th as name_th_unit','unit.name_en as name_en_unit','re.sorting','re.receiving_cost')
        ->where(['re.sorting'=>'yes','sort.packing'=>'no','sort.status'=>'on'])->orderBy('sorting_date','desc')
        ->when($request->keyword,function($find)use($keyword){
            $find->where('sort.code','like',"%{$keyword}%")->where(['re.sorting'=>'yes','sort.packing'=>'no','sort.status'=>'on'])
            ->orWhere('it.name_th','like',"%{$keyword}%")->where(['re.sorting'=>'yes','sort.packing'=>'no','sort.status'=>'on'])
            ->orWhere('it.name_en','like',"%{$keyword}%")->where(['re.sorting'=>'yes','sort.packing'=>'no','sort.status'=>'on']);
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
            'rows' => $rows,
        ]);
    }

    public function create($id)
    {
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
        ->where('sort.id',$id)->orderBy('po.receive_date','asc')->first();


        // echo"<pre>";
        // print_r($data);
        // die;
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
            'row' => $data,
            'items' => ItemModel::where('type','item')->get(),
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

    public function store($id,Request $request)
    {

// echo"<pre>";
//         print_r($request->all());
//         die;
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
        $pack->wastage_percent = floatval($request->wastage);
        //$pack->wastage_percent = 1-floatval($request->wastage);
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
            $de = new PackingDetailModel;
            $de->packing = $pack->id;
            $de->product = $request->ean[$i];
            $de->number_pack = $request->quantity[$i];
            $de->product_weight = $request->weight[$i];
            $de->cost_packaging = $request->cost_packaging[$i];
            $de->wrap_cost = $request->wrap_cost[$i];
            $de->plus_cost = $request->plus_cost[$i];
            $de->balance = $request->quantity[$i];
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
    
    public function getView(Request $request)
    {
        $id = $request->id;
        $data = SortingModel::find($id);
        $text = $data->note;
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

    // public function getEANStock(Request $request)
    // {
    //      $id = $request->id;
    //      $usedQty = $request->usedQty;
   
    //     // $sql= DB::table('sorting as s')->select('a.balance')->join('products as p','p.id=')->where('type','!=','item')->where('product',$id);
    //     // if($sql->count()==0)
    //     // {
    //     //     return 0;
    //     // }

    //     // $data=$sql->get();

        
    //     return 1;

    //   //return $data;
    // }

    public function getCostPack(Request $request)
    {
        /*  old code start
         $id = $request->id;
       // $datas = DB::table('setups as set')
       // ->leftJoin('receiving as re','set.item','=','re.item')
        //->leftJoin('imports as im','re.import','=','im.id')
        //->where(['set.type'=>'packaging','set.product'=>$id])
        //->orderBy('re.receive_date','asc')->get();
        
         $datas = DB::table('setups as set')
        ->leftJoin('receiving as re','set.item','=','re.item')
        ->leftJoin('imports as im','re.import','=','im.id')
        ->where(['set.type'=>'packaging','set.product'=>$id])
        ->orderBy('re.receive_date','desc')->first();
        
        $sum_cost = 0;
        $qty = 0;
   


        
     
      
//echo json_encode($datas);
        $qty=$datas->qty; 
        $sum_cost=($datas->price*(int)$datas->qty);

       /* foreach($datas as $key => $data) 
        {
            $qty+=$data->qty;  

            $sum_cost+=($data->price*(int)$data->qty);

           
        }*/
      /*
        //$text['sum_cost'] = $sum_cost;
        //$text['qty'] = $qty;
     
       //return $text;
       // old code end
        */
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

    public function waste(Request $request)
    {
        $id = $request->id;
        $data = SortingModel::find($id);
        $data->status = 'off';
        if($data->save()){
            return 'true';
        }else{
            return 'false';
        }
    }




   public function registerWaste(request $request)
    {


        $tran = new TransectionModel;
        $receiving_cost=$request->receiving_cost;
        $transactionValue= $receiving_cost*$request->wasteQty*-1;
        $tran->receiving_cost = $receiving_cost;
        $tran->transactionValue = $transactionValue;
        $tran->transection_type = 'waste';
        $tran->transection_menu = 'damage';
        $tran->ref_id = 0;
        $tran->item_id = $request->item_id;
        $tran->code = $request->code;
        $tran->type = "item";
        $tran->unit = $request->unit;
        $tran->qty = (floatval($request->wasteQty)*-1);
        $tran->good_qty = floatval($request->goodQty);
        $tran->waste_qty = $request->wasteQty*-1;
        $tran->transection_date = date('Y-m-d H:i:s');
       // $tran->save();
     
 if($tran->save())
        {

            DB::table("sorting")->where("code",$request->code)->update(["balance"=>$request->goodQty]);


        $id = $request->rowID;
        $data = SortingModel::find($id);
        if($request->goodQty<1)
        {
            $data->status = 'off';
        }
        //$data->status = 'off';
        $data->save();

            return view("$this->prefix.alert.sweet.success",['url'=>url("$this->folder")]);
        }else{
            return view("$this->prefix.alert.sweet.error",['url'=>$request->fullUrl()]);
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
}