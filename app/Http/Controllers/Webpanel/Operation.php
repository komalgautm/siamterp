<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\AirportModel;
use App\AirlineModel;
use App\ClientModel;
use App\ShipToModel;
use App\ClearanceModel;
use App\ITFModel;
use App\ITFdetailModel;
use App\UnitCountModel;
use App\QuotationModel;
use App\QuotationDetailModel;
use App\TransportModel;
use App\CurrencyModel;
use App\FreightModel;
use App\OrderModel;
use App\OrderDetailModel;
use App\POLModel;
use App\PackingDetailModel;
use App\CalCostModel;
use App\ReceiveModel;
use App\OperationModel;
use App\AverageModel;

class Operation extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'operation';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = OrderModel::where('status','pending')->orderBy('created','desc')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('code','like',"%{$keyword}%");
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
            'segment' => $this->segment,
            'rows' => $rows,
        ]);
    }

    public function edit($id)
    {
        $data = OrderModel::find($id);
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
            'page' => 'edit',
            'segment' => $this->segment,
            'row' => $data,
            'details' => OperationModel::select('operation.*','detail.ean_qty','detail.nw','detail.number_box','detail.qty','detail.itf_id','itf.name','detail.hpl_avg_weight','detail.new_weight','detail.net_weight')->leftJoin('orders_detail as detail','operation.order_detail_id','=','detail.id')->leftJoin('itf','detail.itf_id','=','itf.id')
            ->where('operation.order_id',$data->id)->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        foreach($request->operation_id as $key => $val){
            $detail = OperationModel::find(@$request->operation_id[$key]);
            if(@$detail){
                $detail->box = $request->box[$key];
                $detail->packing_pallet = $request->packing_pallet[$key];
                $detail->updated = date('Y-m-d H:i:s');
                $detail->save();
            }
        }
        if($request->operation_id)
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function pack(Request $request)
    {
        $operation_id = $request->operation_id;
        $box = $request->box;
        $packaging = $request->packaging;
        $packing_weight = $request->packing_weight;
        $adjust = $request->adjust;
        $over_nw = $request->nw;
        $data = OperationModel::select('operation.*','detail.order_id','detail.id as detail_id','detail.ean_id','detail.itf_id','detail.number_box','detail.ean_qty','detail.box_pallet')
        ->leftJoin('orders_detail as detail','operation.order_detail_id','=','detail.id')->where('operation.id',$operation_id)->first();
        
        $itfs = ITFdetailModel::where('itf',$data->itf_id)->get();
        $count = count($itfs->where('type','ean'));

        $select_pallet=OrderModel::select("select_pallet")->where("id",$data->order_id)->first()->select_pallet;
        $of_pallets=0;
        if($select_pallet=='yes')
        {
            $of_pallets=($box/$data->box_pallet);
        }


        $new_gw=(($packing_weight+($adjust/1000))*($box))+$over_nw; 

        $data->box = $box;
        $data->packaging = $packaging;
        $data->adjust = $adjust;
        $data->new_gw = $new_gw;
        $data->over_nw = $over_nw;
        $data->of_pallets = $of_pallets;
        OrderModel::where(['id'=>$data->order_id])->update(['isLocked'=>'1']);
        foreach($itfs as $itf)
        {
            
            if($itf->type == 'ean')
            {

                $first = PackingDetailModel::select('pack.id as pack_id','packing_detail.cost_ean as ean_cost','packing_detail.id','packing_detail.balance','pro.code as ean_code','pro.name','packing_detail.ean_unit','packing_detail.net_weight as avg_w')
                ->leftJoin('packing as pack','packing_detail.packing','=','pack.id')
                ->leftJoin('products as pro','packing_detail.product','=','pro.id')
                ->where('packing_detail.product',$itf->item)->where('packing_detail.balance','!=',0)->where('pack.status','on')
                ->orderBy('pack.created','asc')->first();

                OrderDetailModel::where(['order_id'=>$data->order_id,'itf_id'=>$data->itf_id])->update(['ean_id'=>$itf->item]);
                $qty=$over_nw; 
                if($first->ean_unit=='1')
                {
                     $qty=floatval($over_nw)/(floatval($first->avg_w)/1000);
                }
                $qty=round($qty,3);

                if($qty > $first->balance)
                {
                     $cal = floatval($qty)-floatval($first->balance);
                    if($first->ean_unit=='2')
                    {

                      $cal=floatval($qty)-floatval($first->balance)*(floatval($first->avg_w)/1000);
                    }
                     $cal=round($cal);
                      $qty_nw=$cal;
                    if($first->ean_unit=='1')
                    {
                      $qty_nw=$cal*(floatval($first->avg_w)/1000);
                    }

                    // echo"<br>line 1 deducted= $first->balance <br>";
                    // echo"<br>line 1 rest qty $cal <br>";
                    
                  
                    $pol = new POLModel;
                    $pol->order_id = $data->order_id;
                    $pol->order_detail_id = $data->detail_id;
                    $pol->packing_detail_id = $first->id;
                    $pol->itf_id = $data->itf_id;
                    $pol->ean_id = $itf->item;
                    $pol->name = $first->name;
                    $pol->ean_code = $first->ean_code;
                    $pol->qty = $first->balance;
                    $pol->deduction = $first->balance;
                  
                    $pol->cost = $first->ean_cost;
                    $pol->sum_cost = floatval($first->balance)*floatval($first->ean_cost);
                    $pol->created = date('Y-m-d H:i:s');
                    $pol->save();

                    $first->balance = 0;
                    $first->save();




                    $second = PackingDetailModel::select('pack.id as pack_id','pack.ean_cost','packing_detail.id','packing_detail.balance','pro.code as ean_code','pro.name','packing_detail.ean_unit','packing_detail.net_weight as avg_w','packing_detail.cost_ean as ean_cost')
                    ->leftJoin('packing as pack','packing_detail.packing','=','pack.id')
                    ->leftJoin('products as pro','packing_detail.product','=','pro.id')
                    ->where('packing_detail.product',$itf->item)->where('packing_detail.balance','!=',0)->where('pack.status','on')
                    ->orderBy('pack.created','asc')->get();


// echo"<br>second ".count($second)."<br>";
                    foreach($second as $k => $sec)
                    {
                       $qty=round($qty_nw);
                        if($sec->ean_unit=='1')
                        {
                             $qty=floatval($qty_nw)/(floatval($sec->avg_w)/1000);
                        }
                        $qty=round($qty);

                        OrderDetailModel::where(['order_id'=>$data->order_id,'itf_id'=>$data->itf_id])->update(['ean_id'=>$itf->item]);

                        
                        $pol = new POLModel;
                        $pol->order_id = $data->order_id;
                        $pol->order_detail_id = $data->detail_id;
                        $pol->packing_detail_id = $sec->id;
                        $pol->itf_id = $data->itf_id;
                        $pol->ean_id = $itf->item;
                        $pol->name = $sec->name;
                        $pol->ean_code = $sec->ean_code;
                        $pol->created = date('Y-m-d H:i:s');
                
                        // echo"<br>qtyooo a $qty  $sec->balance  <br>";
                   

                        if($qty >= $sec->balance)
                        {
                            

                        $cal = floatval($qty)-floatval($sec->balance);

                        if($sec->ean_unit=='2')
                        {

                          $cal=floatval($qty)-floatval($sec->balance)*(floatval($sec->avg_w)/1000);
                        }

                        $cal=round($cal);

                            $qty_nw=$cal;
                            if($sec->ean_unit=='1')
                            {
                              $qty_nw=$cal*(floatval($sec->avg_w)/1000);
                            }

                            $pol->qty = $sec->balance;
                            $pol->deduction = $sec->balance;
                            $pol->cost = $sec->ean_cost;
                            $pol->sum_cost = floatval($sec->balance)*floatval($sec->ean_cost);
                            $pol->save();

                            
                            if($cal!=0)
                            {
                                 $cal = $cal-$sec->balance; 
                            }
                          
                            $sec->balance = 0;
                            $sec->save();

                    // echo"<br>line 2 deducted= $pol->qty <br>";
                    // echo"<br>line 2 rest qty $cal <br>";

                        }else{
                            $cal = floatval($sec->balance)-floatval($qty);


 // echo"<br>qtyooob $qty  $sec->balance  <br>";

                            if($sec->ean_unit=='2')
                            {

                              $cal=floatval($sec->balance)*(floatval($sec->avg_w)/1000)-floatval($qty);
                            }

                            $pol->qty = $qty;
                            $pol->deduction = $qty;
                            $pol->cost = $sec->ean_cost;
                            $pol->sum_cost = floatval($qty)*floatval($sec->ean_cost);
                            $pol->save();
                          

                            
                            $sec->balance = $cal;
                            $sec->save();  
                    //          echo"<br>line last deducted= $qty <br>";
                    // echo"<br>line last  balance $cal <br>";        

                         break;
                        }
                        // if($cal == 0) 
                        //     break;
                    }
                }
                else
                {
                    $cal = floatval($first->balance)-floatval($qty);
                    $pol = new POLModel;
                    $pol->order_id = $data->order_id;
                    $pol->order_detail_id = $data->detail_id;
                    $pol->packing_detail_id = $first->id;
                    $pol->itf_id = $data->itf_id;
                    $pol->ean_id = $itf->item;
                    $pol->name = $first->name;
                    $pol->ean_code = $first->ean_code;
                    $pol->qty = $qty;
                    $pol->deduction = $qty;
                    $pol->cost = $first->ean_cost;
                    $pol->sum_cost = floatval($qty)*floatval($first->ean_cost);
                    $pol->created = date('Y-m-d H:i:s');
                    $pol->save();

                    $first->balance = $cal;
                    $first->save();
                }
                $check_aver = AverageModel::where('order_id',$data->order_id)->where('ean_id',$itf->item)->count();
                if($check_aver == 0)
                {
                 
                    $average = new AverageModel;
                    $average->order_id = $data->order_id;
                    $average->ean_id = $itf->item;
                    $average->save();
                }
            }
            $main_pol = POLModel::where('order_id',$data->order_id)->where('ean_id',$itf->item);
            $qty = $main_pol->sum('qty');
            $sum_cost = $main_pol->sum('sum_cost');
            if($qty && $sum_cost)
            {
                $average = AverageModel::where('order_id',$data->order_id)->where('ean_id',$itf->item)->first();

                $average->average = floatval($sum_cost)/floatval($qty);
                $average->save();

                //echo"order_id=$data->order_id  itf_id=$data->itf_id  item= $itf->item";
               
            }
        }
        if($data->save()){
            return 'true';
        }else{
            return 'false';
        }
    }

    public function unpack(Request $request)
    {
        $operation_id = $request->operation_id;
        $data = OperationModel::select('operation.*','detail.order_id','detail.id as detail_id','detail.ean_id','detail.itf_id','detail.ean_qty')
        ->leftJoin('orders_detail as detail','operation.order_detail_id','=','detail.id')->where('operation.id',$operation_id)->first();
		$order_id = $data->order_id;
		$status = OrderModel::where('id',$order_id)->first()->status;
		
		if($status == 'confirm')
		{
			return 'confirm';
		}
        else
		{
			$data->box = null;
			$data->packaging = null;
			$itfs = ITFdetailModel::where('itf',$data->itf_id)->get();
			foreach($itfs as $itf){
				if($itf->type == 'ean'){
					$pols = POLModel::where('order_id',$data->order_id)->where('itf_id',$itf->itf)->get();
					foreach($pols as $pol){
						$packing = PackingDetailModel::where('id',$pol->packing_detail_id)->first();
						$packing->balance = $packing->balance+$pol->qty;
						$packing->save();

						$pol->delete();
					}
					$check_pol = POLModel::where('order_id',$data->order_id)->where('ean_id',$itf->item)->count();
					if($check_pol == 0){
						$average = AverageModel::where('order_id',$data->order_id)->where('ean_id',$itf->item)->delete();
					}else{
						$qty = POLModel::where('order_id',$data->order_id)->where('ean_id',$itf->item)->sum('qty');
						$sum_cost = POLModel::where('order_id',$data->order_id)->where('ean_id',$itf->item)->sum('sum_cost');
						if($qty && $sum_cost){
							$average = AverageModel::where('order_id',$data->order_id)->where('ean_id',$itf->item)->first();
							$average->average = floatval($sum_cost)/floatval($qty);
							$average->save();
						}
					}
				}
			}
			if($data->save())
			{
				$count = AverageModel::where('id',$data->order_id)->count();
				if($count==0)
				{
					 OrderModel::where(['id'=>$data->order_id])->update(['isLocked'=>'0']); 
				}
			  
				return 'true';
			}else{
				return 'false';
			}
		}
    }

    public function check(Request $request)
    {
        $operation_id = $request->operation_id;
        $box = $request->box;
        $nw = $request->nw;
        $data = OperationModel::select('operation.*','detail.order_id','detail.id as detail_id','detail.ean_id','detail.itf_id','detail.ean_qty')
        ->leftJoin('orders_detail as detail','operation.order_detail_id','=','detail.id')->where('operation.id',$operation_id)->first();
        $itfs = ITFdetailModel::where('itf',$data->itf_id)->get();
        $count = count($itfs->where('type','ean'));
        $balance = 0;
        $rs_count;
        $rs_sum;
        $over_nw=$nw;

        foreach($itfs as $itf)
        {
            if($itf->type == 'ean')
            {
                if($count > 1)
                {
                    $qty = (floatval($box)*floatval($itf->qty))/$count;
                }
                else
                {
                    $qty = floatval($box)*floatval($itf->qty);
                }
                $count_pack = DB::table('packing_detail as detail')->leftJoin('packing as pack','detail.packing','=','pack.id')
                ->where('detail.product',$itf->item)->count();
                if($count_pack != 0)
                {
                    $balance = DB::table('packing_detail as detail')->leftJoin('packing as pack','detail.packing','=','pack.id')
                    ->where('detail.product',$itf->item)->sum('detail.balance');


                    $rs_count = 'true';
                }
                else
                {
                    $rs_count = 'false';
                }
                $net_weight = DB::table('packing_detail as detail')->leftJoin('packing as pack','detail.packing','=','pack.id')->where('detail.product',$itf->item)->first()->net_weight;
               $qty=floatval($over_nw)/(floatval($net_weight)/1000);
            }
              


       
            if($balance >= $qty)
            {
                $rs_sum = 'true';
            }
            else
            {
                $rs_sum = 'false';
                break;
            }
        }

        $text['count'] = $rs_count;
        $text['sum'] = $rs_sum;
         // echo$balance;
        return $text;
    }


    public function confirmPack(Request $request)
    {
        $id=$request->id;
        $all=DB::table('operation')->where('order_id',$id)->count();
        $done=DB::table('operation')->where('order_id',$id)->where('packaging','!=',null)->count();
        $or['status']=true;
      
        if($all!=$done)
        {
           $or['percent']=($done/$all)*100;
           $or['status']=false;
           return response()->json($or);
        }
        DB::table('orders')->where('id',$id)->update(array('packedStatus'=>'1'));
        return response()->json($or);
    }

    // public function getAverage(Request $request)
    // {
    //     $average_lot = 0;
    //     $c = array();
    //     $checks = json_decode($request->check);
    //     foreach($checks as $index => $check){
    //         $cal_ = $check;
    //         $packings = PackingDetailModel::select('pack.id as pack_id','pack.ean_cost','packing_detail.*')
    //         ->leftJoin('packing as pack','packing_detail.packing','=','pack.id')
    //         ->where('packing_detail.product',$index)->where('packing_detail.balance','!=',0)
    //         ->orderBy('pack.created','asc')->get();
    //         foreach($packings as $index_ => $p){
    //             if($cal_ >= $p->cal_balance){
    //                 $cal_ = floatval($cal_)-floatval($p->cal_balance);
    //                 $c[$index_] = floatval($p->cal_balance)*floatval($p->ean_cost);
    //             }else{
    //                 $c[$index_] = floatval($cal_)*floatval($p->ean_cost);
    //                 $cal_ = 0;
    //             }
    //             if($cal_ == 0) break;
    //         }
    //         $sum_c = array_sum($c);
            
    //         $average_lot = $sum_c/$check;
    //     }
    //     return number_format($average_lot,5,".","");
    // }

    // public function checkpacking(Request $request)
    // {
    //     $id = $request->itf_id;
    //     $num_box = $request->num_box;
    //     $datas = ITFdetailModel::where('itf',$id)->get();
    //     $count = array();
    //     $sum = array();
    //     $name = array();
    //     $rs = array();
    //     $rs_re = array();
    //     $count_re = array();
    //     $sum_re = array();
    //     $name_re = array();
    //     $qty_re = array();
    //     $checks = json_decode($request->check);
    //     foreach($checks as $k => $c)
    //     {
    //         $count[$k] = DB::table('packing_detail as detail')->leftJoin('packing as pack','detail.packing','=','pack.id')
    //         ->where('detail.product',$k)->where('detail.balance','!=',0)->count();
    //         if($count[$k] != 0){
    //             $sum[$k] = DB::table('packing_detail as detail')->leftJoin('packing as pack','detail.packing','=','pack.id')
    //             ->where('detail.product',$k)->where('detail.balance','!=',0)->sum('detail.balance');
    //             $query = DB::table('products')->where('id',$k)->first();
    //             $name[$k] = $query->name;
    //         }
    //         if($sum[$k] >= $c){
    //             $rs[$k] = 'true';
    //         }else{
    //             $rs[$k] = 'false';
    //         }
    //         foreach($datas as $key => $data)
    //         {
    //             if($data->type != 'ean'){
    //                 $count_re[$key] = DB::table('receiving as re')->leftJoin('imports as im','re.import','=','im.id')
    //                 ->where('re.item',$data->item)->where('re.balance','!=',0)->count();
    //                 if($count_re[$key] != 0){
    //                     $sum_re[$key] = DB::table('receiving as re')->leftJoin('imports as im','re.import','=','im.id')
    //                     ->where('re.item',$data->item)->where('re.balance','!=',0)->sum('re.balance');
    //                     $query = DB::table('items')->where('id',$data->item)->first();
    //                     $name_re[$key] = $query->name_th;
    //                     $num_qty = ITFdetailModel::where('itf',$id)->where('type','!=','ean')->first();
    //                     $qty_re[$key] = $num_qty->qty;
    //                 }
    //                 if($sum_re[$key] >= $num_box*$qty_re[$key]){
    //                     $rs_re[$k] = 'true';
    //                 }else{
    //                     $rs_re[$k] = 'false';
    //                     break;
    //                 }
    //             }
    //         }
    //     }
    //     $text['rs'] = $rs;
    //     $text['rs_re'] = $rs_re;
    //     $text['count'] = $count;
    //     $text['sum'] = $sum;
    //     $text['name'] = $name;
    //     $text['count_re'] = $count_re;
    //     $text['sum_re'] = $sum_re;
    //     $text['name_re'] = $name_re;
    //     $text['qty_re'] = $qty_re;
    //     return $text;
    // }

    // public function getCost(Request $request)
    // {
    //     $cost = array();
    //     $average = $request->average;
    //     $details = $request->myDetail;
    //     foreach($details as $index => $detail){
    //         $datas = ITFdetailModel::where('itf',$detail['itf_id'])->get();
    //         $pd_id = array();
    //         $c = array();
    //         $s = array();
    //         $sum_c = 0;
    //         $sum_s = 0;
    //         foreach($datas as $key => $data)
    //         {
    //             if($data->type == 'ean'){
    //                 $first = PackingDetailModel::select('pack.id as pack_id','pack.ean_cost','packing_detail.*')
    //                 ->leftJoin('packing as pack','packing_detail.packing','=','pack.id')
    //                 ->where('packing_detail.product',$data->item)->where('packing_detail.balance','!=',0)->where('packing_detail.cal_balance','!=',0)
    //                 ->orderBy('pack.created','asc')->first();
    //                 $qty = floatval($detail['num_box'])*floatval($data->qty);
    //                 if($qty > $first->cal_balance){
    //                     $cal = floatval($qty)-floatval($first->cal_balance);
    //                     // $cost = floatval($first->cal_balance)*floatval($first->ean_cost);
    //                     $pd_id[$key][0]['id'] = $first->id;
    //                     $pd_id[$key][0]['type'] = 'itf';
    //                     $pd_id[$key][0]['balance'] = $first->cal_balance;
    //                     $pd_id[$key][0]['cal'] = $cal;
    //                     $cal_cost = new CalCostModel;
    //                     $cal_cost->itf_id = $detail['itf_id'];
    //                     $cal_cost->_id = $first->id;
    //                     $cal_cost->type = 'itf';
    //                     $cal_cost->qty = $first->cal_balance;
    //                     // $cal_cost->cost_price = $cost;
    //                     $cal_cost->save();

    //                     $first->cal_balance = 0;
    //                     $first->save();

    //                     // $count = PackingDetailModel::leftJoin('packing as pack','packing_detail.packing','=','pack.id')
    //                     // ->where('packing_detail.product',$data->item)->where('packing_detail.balance','!=',0)->where('packing_detail.cal_balance','!=',0)->count();
    //                     // $skip = 1;
    //                     // $limit = $count-$skip;
    //                     $second = PackingDetailModel::select('pack.id as pack_id','pack.ean_cost','packing_detail.*')
    //                     ->leftJoin('packing as pack','packing_detail.packing','=','pack.id')
    //                     ->where('packing_detail.product',$data->item)->where('packing_detail.balance','!=',0)->where('packing_detail.cal_balance','!=',0)
    //                     // ->skip($skip)->take($limit)
    //                     ->orderBy('pack.created','asc')->get();
    //                     foreach($second as $k => $sec)
    //                     {
                        
    //                         if($cal >= $sec->cal_balance){
    //                             // $c[$k] = floatval($sec->cal_balance)*floatval($sec->ean_cost);
    //                             $pd_id[$key][$k+1]['id'] = $sec->id;
    //                             $pd_id[$key][$k+1]['type'] = 'itf';
    //                             $pd_id[$key][$k+1]['balance'] = $sec->cal_balance;
    //                             $cal_cost = new CalCostModel;
    //                             $cal_cost->itf_id = $detail['itf_id'];
    //                             $cal_cost->_id = $sec->id;
    //                             $cal_cost->type = 'itf';
    //                             $cal_cost->qty = $sec->cal_balance;
    //                             // $cal_cost->cost_price = $c[$k];
    //                             $cal_cost->save();
    //                             $cal = floatval($cal)-floatval($sec->cal_balance);
    //                             $pd_id[$key][$k+1]['cal'] = $cal;
    //                             $sec->cal_balance = 0;
    //                             $sec->save();
    //                         }else{
    //                             // $c[$k] = floatval($cal)*floatval($sec->ean_cost);
    //                             $pd_id[$key][$k+1]['id'] = $sec->id;
    //                             $pd_id[$key][$k+1]['type'] = 'itf';
    //                             $pd_id[$key][$k+1]['balance'] = $cal;
    //                             $cal_cost = new CalCostModel;
    //                             $cal_cost->itf_id = $detail['itf_id'];
    //                             $cal_cost->_id = $sec->id;
    //                             $cal_cost->type = 'itf';
    //                             $cal_cost->qty = $cal;
    //                             // $cal_cost->cost_price = $c[$k];
    //                             $cal_cost->save();

    //                             $sec->cal_balance = floatval($sec->cal_balance)-floatval($cal);
    //                             $sec->save();

    //                             $cal = floatval($sec->cal_balance)-floatval($cal);
    //                             $pd_id[$key][$k+1]['cal'] = $cal;
    //                         }
    //                         if($cal == 0) break;
    //                     }
    //                     // array_push($c,$cost);
    //                     // $sum_c = array_sum($c);
    //                 }else{
    //                     $cal = floatval($first->cal_balance)-floatval($qty);
    //                     // $cost = floatval($qty)*floatval($first->ean_cost);
    //                     $pd_id[$key][0]['id'] = $first->id;
    //                     $pd_id[$key][0]['type'] = 'itf';
    //                     $pd_id[$key][0]['balance'] = $qty;
    //                     $cal_cost = new CalCostModel;
    //                     $cal_cost->itf_id = $detail['itf_id'];
    //                     $cal_cost->_id = $first->id;
    //                     $cal_cost->type = 'itf';
    //                     $cal_cost->qty = $qty;
    //                     // $cal_cost->cost_price = $cost;
    //                     $cal_cost->save();

    //                     $first->cal_balance = $cal;
    //                     $first->save();

    //                     // array_push($c,$cost);
    //                     // $sum_c = array_sum($c);
    //                 }
                    
    //             }else{
    //                 $qty_re = floatval($detail['num_box'])*floatval($data->qty);
    //                 $re_first = ReceiveModel::select('receiving.id','im.price','receiving.balance','receiving.cal_balance','receiving.type')
    //                 ->leftJoin('imports as im','receiving.import','=','im.id')
    //                 ->where('receiving.item',$data->item)->where('receiving.balance','!=',0)->where('receiving.cal_balance','!=',0)
    //                 ->orderBy('receiving.receive_date','asc')->first();
    //                 if($qty_re > $re_first->cal_balance){
    //                     $cal = floatval($qty_re)-floatval($re_first->balance);
    //                     $cost_re = floatval($re_first->balance)*floatval($re_first->price);
    //                     $pd_id[$key][0]['id'] = $re_first->id;
    //                     $pd_id[$key][0]['type'] = $re_first->type;
    //                     $pd_id[$key][0]['balance'] = $re_first->cal_balance;
    //                     $cal_cost = new CalCostModel;
    //                     $cal_cost->itf_id = $detail['itf_id'];
    //                     $cal_cost->_id = $re_first->id;
    //                     $cal_cost->type = $re_first->type;
    //                     $cal_cost->qty = $re_first->cal_balance;
    //                     // $cal_cost->cost_price = $cost_re;
    //                     $cal_cost->save();

    //                     $re_first->cal_balance = 0;
    //                     $re_first->save();

    //                     // $count = ReceiveModel::leftJoin('imports as im','re.import','=','im.id')
    //                     // ->where('re.item',$data->item)->where('re.balance','!=',0)->where('re.cal_balance','!=',0)->count();
    //                     // $skip = 1;
    //                     // $limit = $count-$skip;
    //                     $re_second = ReceiveModel::select('receiving.id','im.price','receiving.balance','receiving.cal_balance','receiving.cal_balance','receiving.type')
    //                     ->leftJoin('imports as im','receiving.import','=','im.id')
    //                     ->where('receiving.item',$data->item)->where('receiving.balance','!=',0)->where('receiving.cal_balance','!=',0)
    //                     // ->skip($skip)->take($limit)
    //                     ->orderBy('receiving.receive_date','asc')->get();
    //                     foreach($re_second as $k => $sec)
    //                     {
    //                         if($cal >= $sec->cal_balance){
    //                             $s[$k] = floatval($sec->cal_balance)*floatval($sec->price);
    //                             $pd_id[$key][$k+1]['id'] = $sec->id;
    //                             $pd_id[$key][$k+1]['type'] = $sec->type;
    //                             $pd_id[$key][$k+1]['balance'] = $sec->cal_balance;
    //                             $cal_cost = new CalCostModel;
    //                             $cal_cost->itf_id = $detail['itf_id'];
    //                             $cal_cost->_id = $sec->id;
    //                             $cal_cost->type = $sec->type;
    //                             $cal_cost->qty = $sec->cal_balance;
    //                             // $cal_cost->cost_price = $s[$k];
    //                             $cal_cost->save();
    //                             $cal = floatval($cal)-floatval($sec->cal_balance);

    //                             $sec->cal_balance = 0;
    //                             $sec->save();

    //                         }else{
    //                             $s[$k] = floatval($cal)*floatval($sec->price);
    //                             $pd_id[$key][$k+1]['id'] = $sec->id;
    //                             $pd_id[$key][$k+1]['type'] = $sec->type;
    //                             $pd_id[$key][$k+1]['balance'] = $cal;
    //                             $cal_cost = new CalCostModel;
    //                             $cal_cost->itf_id = $detail['itf_id'];
    //                             $cal_cost->_id = $sec->id;
    //                             $cal_cost->type = $sec->type;
    //                             $cal_cost->qty = $cal;
    //                             // $cal_cost->cost_price = $s[$k];
    //                             $cal_cost->save();
                                
    //                             $sec->cal_balance = floatval($sec->cal_balance)-floatval($cal);
    //                             $sec->save();

    //                             $cal = floatval($sec->cal_balance)-floatval($cal);
    //                         }
    //                         if($cal == 0) break;
    //                     }
    //                     array_push($s,$cost_re);
    //                     $sum_s = array_sum($s);
    //                 }else{
    //                     $cal = floatval($re_first->cal_balance)-floatval($qty_re);
    //                     $cost_re = floatval($qty_re)*floatval($re_first->price);
    //                     $pd_id[$key][0]['id'] = $re_first->id;
    //                     $pd_id[$key][0]['type'] = $re_first->type;
    //                     $pd_id[$key][0]['balance'] = $qty_re;
    //                     $cal_cost = new CalCostModel;
    //                     $cal_cost->itf_id = $detail['itf_id'];
    //                     $cal_cost->_id = $re_first->id;
    //                     $cal_cost->type = $re_first->type;
    //                     $cal_cost->qty = $qty_re;
    //                     // $cal_cost->cost_price = $cost_re;
    //                     $cal_cost->save();

    //                     $re_first->cal_balance = $cal;
    //                     $re_first->save();

    //                     array_push($s,$cost_re);
    //                     $sum_s = array_sum($s);
    //                 }

    //             }
    //         }
    //         $cost[$index]['itf_id'] = $detail['itf_id'];
    //         $cost[$index]['cost'] = (floatval($detail['qty'])*$average)+$sum_s;
    //     }
    //     $text['pd_id'] = $pd_id;
    //     $text['cost'] = $cost;
    //     return $text;
    // }
}
