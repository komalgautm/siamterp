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
use App\InvoiceModel;
use App\InvoiceDetailModel;
use App\TransectionModel as Model;
use App\SetupModel as SetupModel;

class Order extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'order';

	function ThreeDigit($no,$lst=3)
	{
		$d=date('m');
		$x=explode($d,$no);
		$x1=explode($x[1],$no);
		$c=strval($x[1]);
		if(strlen($c)==4)
		{
		   $last3="$c[1]$c[2]$c[3]";
		}
		else
		{			
		 $last3=$c;
		}
		$new= $x1[0].$last3;
		return $new;

	}
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = OrderModel::orderBy('created','desc')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('code','like',"%{$keyword}%");
                // ->orWhere('city','like',"%{$keyword}%")
                // ->orWhere('airport_code','like',"%{$keyword}%");
            });
         $rows = $query->get();
        /*if($view=='all'){
            $rows = $query->get();
        }else{
            $view = ($request->view)? $view : 10 ;
            $rows = $query->paginate($view);
            $rows->appends(['view' => $request->view,'keyword' => $keyword,'page' => $request->page]);
        }*/
        return view("$this->prefix.pages.$this->folder.index",[
            'css'=> [
                '/back-end/css/table-responsive.css',
                '/back-end/css/select2.min.css',
                '//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css'
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'/back-end/js/select2.min.js'],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"],
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

    public function create()
    {
        return view("$this->prefix.pages.$this->folder.index",[
            'css'=> [
                '/back-end/css/table-responsive.css',
                '/back-end/css/select2.min.css'
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
            'clients' => ClientModel::where('status','on')->get(),
            'airports' => AirportModel::where('status','on')->get(),
            'airlines' => AirlineModel::where('status','on')->get(),
            'currencys' => CurrencyModel::where('status','on')->get(),
            'clearance' => ClearanceModel::select('clearance.*','v.name')->leftJoin('vendors as v','clearance.vendor','=','v.id')->where('clearance.status','on')->get(),
            'itfs' => ITFModel::where('status','on')->get(),
            'units' => UnitCountModel::whereIn('id', [1,2,5])->get(),
            'pall' => DB::table("items")->where('type','pallets')->first(),
        ]);
    }

    public function store(Request $request)
    {
        
        $order = new OrderModel;
        $order->code = OrderModel::getOCode();

        $shipto = ShipToModel::where('id',$request->shipto)->first();
        $tt_ref = OrderModel::getTTRef($shipto->id,$shipto->code); 
        // echo"$tt_ref <pre>";
        // print_r(json_decode(json_encode($shipto)));
        // die;

        $order->tt_ref =$tt_ref;


      
        $order->user_id = $request->user;
        $order->client_id = $request->client;
        $order->shipto_id = $request->shipto;
        $order->airport_id = $request->airport;
        $order->airline_id = $request->airline;
        $order->clearance_id = $request->select_clearance;
        $order->select_pallet = $request->select_pallet;
        $order->select_chamber = $request->select_chamber;
        $order->currency_id = $request->currency;
        $order->created = date('Y-m-d H:i:s');
        $order->updated = date('Y-m-d H:i:s');
        $order->status = 'pending';
        $order->ship_status = 'pending';
        $order->ex_rate = $request->ex_rate;
        $order->markup_rate = $request->markup_rate;
        $order->rebate = $request->rebate;
        $order->ship_date = $request->ship_date;
        $order->clearance = $request->clearance;
        $order->chamber = $request->chamber;
        $order->clearance_price = $request->clearance_price;
        $order->transport = $request->transport;
        $order->transport_price = $request->transport_price;
        $order->total_box = $request->total_box;
        $order->total_nw = $request->total_nw;
        $order->total_gw = $request->total_gw;
        $order->total_cbm = $request->total_cbm;
        $order->palletized = $request->palletized;
        $order->palletized_price = $request->palletized_price;
        $order->total_freight = $request->total_freight;
        $order->total_fob = $request->total_fob;
        $order->total_pro_before_rebate = $request->total_pro_before_rebate;
        $order->total_pro_after_rebate = $request->total_pro_after_rebate;
        $order->total_pro_percent = $request->total_pro_percent;

        $order->total_cnf = $request->total_cnf;
        $order->markup_rateCal = $request->markup_rateCal;
        $order->freights = $request->freights;
 

        $total_fob= floatval(preg_replace("/[^-0-9\.]/","",$request->total_fob));
        $total_freight= floatval(preg_replace("/[^-0-9\.]/","",$request->total_freight));     
        $order->total_cnf = floatval($total_fob)+floatval($total_freight);
            
            // echo"<pre>";
            // print_r($request->all());
            // die;
       

        if($order->save())
        {
            for($i=0; $i<count($request->itf); $i++)
            {
                $detail = new OrderDetailModel;
                $detail->order_id = $order->id;
                $detail->itf_id = $request->itf[$i];
                $detail->qty = $request->quantity[$i];
                $detail->unitcount_id = $request->unitcount[$i];
                // $detail->ean_id = explode(',',$request->ean_id[$i]);
                $detail->ean_id = $request->ean_id[$i];
                $detail->ean_qty = $request->ean_qty[$i];
                $detail->net_weight = $request->net_weight[$i];
                $detail->new_weight = $request->new_weight[$i];
                $detail->maxcbm = $request->maxcbm[$i];
                $detail->maxpallet = $request->maxpallet[$i];
             //   $detail->average_lot = $request->average_lot[$i];
                $detail->number_box = $request->number_box[$i];
                $detail->nw = $request->nw[$i];
                $detail->gw_weight = $request->gw_weight[$i];
                $detail->cbm = $request->cbm[$i];
                $detail->pallet = $request->pallet[$i];
                $detail->price_allocation = $request->price_allocation[$i];
                $detail->price_pallet_unit = $request->price_pallet_unit[$i];
                $detail->itf_pallet_price = $request->itf_pallet_price[$i];
                $detail->itf_clearance_price = $request->itf_clearance_price[$i];
                $detail->itf_transport_price = $request->itf_transport_price[$i];
                $detail->itf_cost_price = $request->itf_cost_price[$i];
                $detail->itf_freight_rate = $request->itf_freight_rate[$i];
                $detail->total_itf_cost = $request->total_itf_cost[$i];
                $detail->unit_price = $request->unit_price[$i];
                $detail->profit = $request->profit[$i];
                $detail->fob = $request->itf_fob[$i];
                $detail->created = date('Y-m-d H:i:s');
                $detail->updated = date('Y-m-d H:i:s');


                $detail->profit2 = $request->profit2[$i];
          
                $detail->net_weightNew = $request->net_weightNew[$i];
                $detail->itf_GW = $request->itf_GW[$i];
                $detail->ean_ppITF = $request->ean_ppITF[$i];
                $detail->itfQty = $request->itfQty[$i];
                $detail->net_weight2 = $request->net_weight2[$i];
                $detail->hpl_avg_weight = $request->hpl_avg_weight[$i];
                $detail->box_pallet = $request->box_pallet[$i];
                $detail->box_weight = $request->box_weight[$i];
                $detail->itf_cal_selling = $request->itf_cal_selling[$i];

                $detail->fx_price = $request->fixPrice[$i];
                $detail->itf_fx_price = $request->itf_fx_price[$i];


                 $detail->itotal_cost = $request->itotal_cost[$i];
                 $detail->rean_cost = $request->rean_cost[$i];
                 $detail->rclearance = $request->rclearance[$i];
                 $detail->rchamber = $request->rchamber[$i];
                 $detail->rtruck = $request->rtruck[$i];
                 $detail->rpallets = $request->rpallets[$i];
                 $detail->rifreight = $request->rifreight[$i];


                $detail->save();

                $oper = new OperationModel;
                $oper->order_id = $order->id;
                $oper->order_detail_id = $detail->id;
                $oper->created = date('Y-m-d H:i:s');
                $oper->save();
            }
            return view("$this->prefix.alert.sweet.success",['url'=>url("$this->folder")]);
        }else{
            return view("$this->prefix.alert.sweet.error",['url'=>$request->fullUrl()]);
        }
    }

    public function show($id)
    {
        $data = OrderModel::find($id);
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
            'page' => 'view',
            'segment' => $this->segment,
            'row' => $data,
            'details' => OrderDetailModel::where('order_id',$data->id)->get(),
            'clients' => ClientModel::where(['status'=>'on','id'=>$data->client_id])->first(),
            'shipto' => ShipToModel::where(['status'=>'on','client'=>$data->client_id])->first(),
            'airports' => AirportModel::where(['status'=>'on','id'=>$data->airport_id])->first(),
            'airlines' => AirlineModel::where(['status'=>'on','id'=>$data->airline_id])->first(),
            'currencys' => CurrencyModel::where(['status'=>'on','id'=>$data->currency_id])->first(),
            'clearance' => ClearanceModel::select('clearance.*','v.name')->leftJoin('vendors as v','clearance.vendor','=','v.id')->where(['clearance.status'=>'on','clearance.id'=>$data->clearance_id])->first(),
            'units' => UnitCountModel::whereIn('id', [1,2,5])->get(),
        ]);
    }

    public function edit($id)
    {
        $data = OrderModel::find($id);

        $isDone="SELECT if(op.packaging is null,'0','1') as isDone  FROM operation as op where op.order_detail_id=orders_detail.id";
        
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
            'details' => OrderDetailModel::select('*')->where('order_id',$data->id)->selectSub($isDone,'isDone')->get(),
            'clients' => ClientModel::where('status','on')->get(),
            'shiptos' => ShipToModel::where(['status'=>'on','client'=>$data->client_id])->get(),
            'shipto' => ShipToModel::where(['status'=>'on','client'=>$data->client_id])->first(),
            'airports' => AirportModel::where('status','on')->get(),
            'airlines' => AirlineModel::where('status','on')->get(),
            'currencys' => CurrencyModel::where('status','on')->get(),
            'clearance' => ClearanceModel::select('clearance.*','v.name')->leftJoin('vendors as v','clearance.vendor','=','v.id')->where('clearance.status','on')->get(),
            'itfs' => ITFModel::where('status','on')->get(),
            'units' => UnitCountModel::whereIn('id', [1,2,5])->get(),
            'pall' => DB::table("items")->where('type','pallets')->first(),
        ]);
    }

    public function update(Request $request, $id)
    {

       
        $order = OrderModel::find($id);
        $order->user_id = $request->user;
        $order->client_id = $request->client;
        $order->shipto_id = $request->shipto;
        $order->airport_id = $request->airport;
        $order->airline_id = $request->airline;
        $order->clearance_id = $request->select_clearance;
        $order->select_pallet = $request->select_pallet;
        $order->select_chamber = $request->select_chamber;
        $order->currency_id = $request->currency;
        $order->updated = date('Y-m-d H:i:s');
        $order->ex_rate = $request->ex_rate;
        $order->markup_rate = $request->markup_rate;
        $order->rebate = $request->rebate;
        $order->ship_date = $request->ship_date;
        $order->clearance = $request->clearance;
        $order->chamber = $request->chamber;
        $order->clearance_price = $request->clearance_price;
        $order->transport = $request->transport;
        $order->transport_price = $request->transport_price;
        $order->total_box = $request->total_box;
        $order->total_nw = $request->total_nw;
        $order->total_gw = $request->total_gw;
        $order->total_cbm = $request->total_cbm;
        $order->palletized = $request->palletized;
        $order->palletized_price = $request->palletized_price;
        $order->total_freight = $request->total_freight;
        $order->freights = $request->freights;
        $order->total_fob = $request->total_fob;
        $order->total_cnf = $request->total_cnf;
        $order->total_pro_before_rebate = $request->total_pro_before_rebate;
        $order->total_pro_after_rebate = $request->total_pro_after_rebate;
        $order->total_pro_percent = $request->total_pro_percent;

        $total_fob= floatval(preg_replace("/[^-0-9\.]/","",$request->total_fob));
        $total_freight= floatval(preg_replace("/[^-0-9\.]/","",$request->total_freight));     
        $order->total_cnf = floatval($total_fob)+floatval($total_freight);

        $order->save();
     
        foreach($request->itf as $key => $val){
            $detail = OrderDetailModel::find(@$request->detail_id[$key]);
            if(@$detail->id){
                $detail->order_id = $order->id;
                $detail->itf_id = $request->itf[$key];
                $detail->qty = $request->quantity[$key];
                $detail->unitcount_id = $request->unitcount[$key];
                // $detail->ean_id = explode(',',$request->ean_id[$key]);
                $detail->ean_id = $request->ean_id[$key];
                $detail->ean_qty = $request->ean_qty[$key];
                $detail->net_weight = $request->net_weight[$key];
                $detail->new_weight = $request->new_weight[$key];
                $detail->maxcbm = $request->maxcbm[$key];
                $detail->maxpallet = $request->maxpallet[$key];
             //   $detail->average_lot = $request->average_lot[$key];
                $detail->number_box = $request->number_box[$key];
                $detail->nw = $request->nw[$key];
                $detail->gw_weight = $request->gw_weight[$key];
                $detail->cbm = $request->cbm[$key];
                $detail->pallet = $request->pallet[$key];
                $detail->price_allocation = $request->price_allocation[$key];
                $detail->price_pallet_unit = $request->price_pallet_unit[$key];
                $detail->itf_pallet_price = $request->itf_pallet_price[$key];
                $detail->itf_clearance_price = $request->itf_clearance_price[$key];
                $detail->itf_transport_price = $request->itf_transport_price[$key];
                $detail->itf_cost_price = $request->itf_cost_price[$key];
                $detail->itf_freight_rate = $request->itf_freight_rate[$key];
                $detail->total_itf_cost = $request->total_itf_cost[$key];
                $detail->unit_price = $request->unit_price[$key];
                $detail->profit = $request->profit[$key];
                $detail->fob = $request->itf_fob[$key];
                $detail->updated = date('Y-m-d H:i:s');




                $detail->net_weightNew = $request->net_weightNew[$key];
                $detail->itf_GW = $request->itf_GW[$key];
                $detail->ean_ppITF = $request->ean_ppITF[$key];
                $detail->itfQty = $request->itfQty[$key];
                $detail->net_weight2 = $request->net_weight2[$key];
                $detail->hpl_avg_weight = $request->hpl_avg_weight[$key];
                $detail->box_pallet = $request->box_pallet[$key];
                $detail->box_weight = $request->box_weight[$key];
                $detail->itf_cal_selling = $request->itf_cal_selling[$key];

                $detail->profit2 = $request->profit2[$key];




                 $detail->itotal_cost = $request->itotal_cost[$key];
                 $detail->rean_cost = $request->rean_cost[$key];
                 $detail->rclearance = $request->rclearance[$key];
                 $detail->rchamber = $request->rchamber[$key];
                 $detail->rtruck = $request->rtruck[$key];
                 $detail->rpallets = $request->rpallets[$key];
                 $detail->rifreight = $request->rifreight[$key];



                $detail->fx_price = $request->fixPrice[$key];
                $detail->itf_fx_price = $request->itf_fx_price[$key];



                $detail->save();
            }else{
                $new = new OrderDetailModel;
                $new->order_id = $order->id;
                $new->itf_id = $request->itf[$key];
                $new->qty = $request->quantity[$key];
                $new->unitcount_id = $request->unitcount[$key];
                // $new->ean_id = explode(',',$request->ean_id[$key]);
                $new->ean_id = $request->ean_id[$key];
                $new->ean_qty = $request->ean_qty[$key];
                $new->net_weight = $request->net_weight[$key];
                $new->new_weight = $request->new_weight[$key];
                $new->maxcbm = $request->maxcbm[$key];
                $new->maxpallet = $request->maxpallet[$key];
             //   $new->average_lot = $request->average_lot[$key];
                $new->number_box = $request->number_box[$key];
                $new->nw = $request->nw[$key];
                $new->gw_weight = $request->gw_weight[$key];
                $new->cbm = $request->cbm[$key];
                $new->pallet = $request->pallet[$key];
                $new->price_allocation = $request->price_allocation[$key];
                $new->price_pallet_unit = $request->price_pallet_unit[$key];
                $new->itf_pallet_price = $request->itf_pallet_price[$key];
                $new->itf_clearance_price = $request->itf_clearance_price[$key];
                $new->itf_transport_price = $request->itf_transport_price[$key];
                $new->itf_cost_price = $request->itf_cost_price[$key];
                $new->itf_freight_rate = $request->itf_freight_rate[$key];
                $new->total_itf_cost = $request->total_itf_cost[$key];
                $new->unit_price = $request->unit_price[$key];
                $new->profit = $request->profit[$key];
                $new->fob = $request->itf_fob[$key];

                $new->net_weightNew = $request->net_weightNew[$key];
                $new->itf_GW = $request->itf_GW[$key];
                $new->ean_ppITF = $request->ean_ppITF[$key];
                $new->itfQty = $request->itfQty[$key];
                $new->net_weight2 = $request->net_weight2[$key];
                $new->hpl_avg_weight = $request->hpl_avg_weight[$key];
                $new->box_pallet = $request->box_pallet[$key];
                $new->box_weight = $request->box_weight[$key];
                $new->itf_cal_selling = $request->itf_cal_selling[$key];



                $new->itotal_cost = $request->itotal_cost[$key];
                $new->rean_cost = $request->rean_cost[$key];
                $new->rclearance = $request->rclearance[$key];
                $new->rchamber = $request->rchamber[$key];
                $new->rtruck = $request->rtruck[$key];
                $new->rpallets = $request->rpallets[$key];
                $new->rifreight = $request->rifreight[$key];
                 
                $new->profit2 = $request->profit2[$key];

                $new->fx_price = $request->fixPrice[$key];
                $new->itf_fx_price = $request->itf_fx_price[$key];


                $new->created = date('Y-m-d H:i:s');
                $new->updated = date('Y-m-d H:i:s');
                $new->save();

                $oper = new OperationModel;
                $oper->order_id = $order->id;
                $oper->order_detail_id = $new->id;
                $oper->created = date('Y-m-d H:i:s');
                $oper->save();
            }   
        }
        if($order->id)
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function destroy($id)
    {
        //
    }

    public function getITF()
    {
        $datas = ITFModel::where('status','on')->get();
        $text = '<option value="">Select ITF</option>';
        foreach($datas as $data)
        {
            $text.='<option value="'.$data->id.'">'.$data->name.'</option>';
        }
        return $text;
    }

    public function getShip(Request $request)
    {
        $id = $request->id;
        $datas = DB::table('shiptos')->where(['status'=>'on','client'=>$id])->get();
        $text = '<option value="">Select ship to</option>';
        foreach($datas as $data)
        {
            $text.='<option value="'.$data->id.'">'.$data->name.'</option>';
        }
        return $text;
    }

    public function getAir(Request $request)
    {
        if($request->shipto_id){
            $shipto_id = $request->shipto_id;
            if($shipto_id != ""){
                $data = ShipToModel::find($shipto_id);
                $air = AirportModel::where(['status'=>'on','id'=>$data->airport])->first();
                $text['airport'] = '<option value="'.$air->id.'">'.$air->country.' - '.$air->city.' ['.$air->airport_code.']'.'</option>';
    
                $airlines = FreightModel::select('freight.*','line.id as airline_id','line.name','line.airline_code')->leftJoin('airline as line','freight.airline','=','line.id')->where(['freight.status'=>'on','destination'=>$air->id])->groupBy('airline')->get();
                $text['airline'] = '<option value="">Select airline</option>';
                foreach($airlines as $line)
                {
                    $text['airline'].='<option value="'.$line->airline_id.'">'.$line->name.' ['.$line->airline_code.']'.'</option>';
                }
            }else{
                $airports = AirportModel::where('status','on')->get();
                $text['airport'] = '<option value="">Select airport</option>';
                foreach($airports as $air)
                {
                    $text['airport'].='<option value="'.$air->id.'">'.$air->country.' - '.$air->city.' ['.$air->airport_code.']'.'</option>';
                }
                $text['airline'] = '<option value="">Select airline</option>';
            }
        }elseif($request->airport_id){
            $airport_id = $request->airport_id;
            if($airport_id != ""){
                $airlines = FreightModel::select('freight.*','line.id as airline_id','line.name','line.airline_code')->leftJoin('airline as line','freight.airline','=','line.id')->where(['freight.status'=>'on','destination'=>$airport_id])->groupBy('airline')->get();
                $text['airline'] = '<option value="">Select airline</option>';
                foreach($airlines as $line)
                {
                    $text['airline'].='<option value="'.$line->airline_id.'">'.$line->name.' ['.$line->airline_code.']'.'</option>';
                }
            }else{
                $text['airline'] = '<option value="">Select airline</option>';
            }
        }else{
            $airports = AirportModel::where('status','on')->get();
            $text['airport'] = '<option value="">Select airport</option>';
            foreach($airports as $air)
            {
                $text['airport'].='<option value="'.$air->id.'">'.$air->country.' - '.$air->city.' ['.$air->airport_code.']'.'</option>';
            }
            $text['airline'] = '<option value="">Select airline</option>';
        }
        return $text;
    }

    public function getRate(Request $request)
    {
       if($request->total_gw)
        {
            $gw = floatval($request->total_gw);
            $destination = $request->destination;
            $airline = $request->airline;
            $clear = ClearanceModel::where('id',$request->clear)->first();
            $data = FreightModel::where(['vendor'=>$clear->vendor,'destination'=>$destination,'airline'=>$airline])->first();
             if(!isset($data))
            {
                return "na";
            }

// echo"<pre>";
//             print_r($data->rate_250);
//        echo"<br>";
            $text['rate'] = 0;
            $text['nego_rate'] = 0;
            if($gw >= 45 && $gw < 100 && floatval($data->rate_45) != 0 || $gw >= 45 && floatval($data->rate_45) != 0 && floatval($data->rate_100) == 0)
            {
                $text['rate'] = $data->rate_45;
            }

            elseif($gw >= 100 && $gw < 250 && floatval($data->rate_100) != 0 || $gw >= 100 && floatval($data->rate_100) != 0 && floatval($data->rate_250) == 0)
            {
                $text['rate'] = $data->rate_100;
            }
            elseif($gw >= 250 && $gw < 500 && floatval($data->rate_250) != 0 || $gw >= 250 && floatval($data->rate_250) != 0 && floatval($data->rate_500) == 0)
            {
                $text['rate'] = $data->rate_250;
            }
            elseif($gw >= 500 && $gw < 1000 && floatval($data->rate_500) != 0 || $gw >= 500 && floatval($data->rate_500) != 0 && floatval($data->rate_1000) == 0)
            {
                $text['rate'] = $data->rate_500;
            }
            elseif($gw >= 1000 && $gw < 2000 && floatval($data->rate_1000) != 0 || $gw >= 1000 && floatval($data->rate_1000) != 0 && floatval($data->rate_2000) == 0)
            {
                $text['rate'] = $data->rate_1000;
            }
            elseif($gw >= 2000 && floatval($data->rate_2000) != 0)
            {
                $text['rate'] = $data->rate_2000;
            }


            if($gw >= 45 && $gw < 100 && floatval($data->nego_rate_45) != 0 || $gw >= 45 && floatval($data->nego_rate_45) != 0 && floatval($data->nego_rate_100) == 0)
            {
                $text['nego_rate'] = $data->nego_rate_45;
            }
            elseif($gw >= 100 && $gw < 250 && floatval($data->nego_rate_100) != 0 || $gw >= 100 && floatval($data->nego_rate_100) != 0 && floatval($data->nego_rate_250) == 0)
            {
                $text['nego_rate'] = $data->nego_rate_100;
            }
            elseif($gw >= 250 && $gw < 500 && floatval($data->nego_rate_250) != 0 || $gw >= 250 && floatval($data->nego_rate_250) != 0 && floatval($data->nego_rate_500) == 0)
            {
                $text['nego_rate'] = $data->nego_rate_250;
            }
            elseif($gw >= 500 && $gw < 1000 && floatval($data->nego_rate_500) != 0 || $gw >= 500 && floatval($data->nego_rate_500) != 0 && floatval($data->nego_rate_1000) == 0)
            {
                $text['nego_rate'] = $data->nego_rate_500;
            }
            elseif($gw >= 1000 && $gw < 2000 && floatval($data->nego_rate_1000) != 0 || $gw >= 1000 && floatval($data->nego_rate_1000) != 0 && floatval($data->nego_rate_2000) == 0)
            {
                $text['nego_rate'] = $data->nego_rate_1000;
            }
            elseif($gw >= 2000 && floatval($data->nego_rate_2000) != 0){
                $text['nego_rate'] = $data->nego_rate_2000;
            }

            // if($data->rate_45 != 0){
            //     $text['min'] = 45;
            // }elseif($data->rate_45 == 0 && $data->rate_100 != 0){
            //     $text['min'] = 100;
            // }elseif($data->rate_45 == 0 && $data->rate_100 == 0 && $data->rate_250 != 0){
            //     $text['min'] = 250;
            // }elseif($data->rate_45 == 0 && $data->rate_100 == 0 && $data->rate_250 == 0 && $data->rate_500 != 0){
            //     $text['min'] = 500;
            // }elseif($data->rate_45 == 0 && $data->rate_100 == 0 && $data->rate_250 == 0 && $data->rate_500 == 0 && $data->rate_1000 != 0){
            //     $text['min'] = 1000;
            // }elseif($data->rate_45 == 0 && $data->rate_100 == 0 && $data->rate_250 == 0 && $data->rate_500 == 0 && $data->rate_1000 == 0 && $data->rate_2000 != 0){
            //     $text['min'] = 2000;
            // }
            return $text;
        }
         return 0;
    }

    public function getUnit()
    {
        $datas = UnitCountModel::whereIn('id', [1,2,5])->get();
        $text = '<option value="">Select Unit</option>';
        foreach($datas as $data)
        {
            $text.='<option value="'.$data->id.'">'.$data->name_th." / ".$data->name_en.'</option>';
        }
        return $text;
    }

    public function getVal(Request $request)
    {
        $id = $request->id;
        //echo $id; die;
        $itf = ITFModel::find($id);
        $datas = ITFdetailModel::where(['type'=>'ean','itf'=>$id])->get();
        $datas1 = ITFdetailModel::where(['itf'=>$id])->get();
        $qty = [];
        $ean_id = [];
        $box_pallet = 0;
        $itfQty = 0;

        foreach($datas as $data)
        {
            $qty[] = $data->qty;
           
            $ean_id[] = $data->item;
            if($data->type=='boxes')
            {
                $box_pallet+=$data->box_pallet;
            }
            //$text['cost_ean']=DB::table("packing_detail")->select('cost_ean')->where("product",$data->item)->orderBy('updated','desc')->first()->cost_ean;
            //$text['hpl_avg_weight']=DB::table("packing_detail")->select('net_weight')->where("product",$data->item)->orderBy('updated','desc')->first()->net_weight;
            
            $cost_ean=DB::table("packing_detail")->select('cost_ean')->where("product",$data->item)->orderBy('updated','desc')->count();
            if($cost_ean > 0)
            {
                $text['cost_ean']=DB::table("packing_detail")->select('cost_ean')->where("product",$data->item)->orderBy('updated','desc')->first()->cost_ean;
                $text['hpl_avg_weight']=DB::table("packing_detail")->select('net_weight')->where("product",$data->item)->orderBy('updated','desc')->first()->net_weight;
            }
            else
            {
               $avg=AverageModel::where(['ean_id'=>$data->item])->orderby("id",'desc');
                if($avg->count()>0)
                {
                    $text['cost_ean']=$avg->first()->average;
                    $text['hpl_avg_weight']="";
                }
                else
                {
                     $text['cost_ean']="";
                     $text['hpl_avg_weight']="";
                }
            }
            
           // $text['hpl_avg_weight']=DB::table("packing_detail")->select('net_weight')->where("product",$data->item)->orderBy('updated','desc')->first()->net_weight;
        }

         foreach($datas1 as $data1)
        {
           
            $itfQty+=$data1->qty;
           
        }
        $text['ean_id'] = $ean_id;
        $text['qty'] = $qty;
        $text['itfQty'] = $itfQty;
        $text['box_pallet'] = $itf->maxbox_pallet;
        $text['net_weight'] = $itf->net_weight;
        $text['ean_ppITF'] = $itf->ean_ppITF;
        if($itf->new_weight != 0){
            $text['new_weight'] = $itf->new_weight;
        }else{
            $text['new_weight'] = $itf->all_weight;
        }
        $text['maxcbm'] = $itf->maxcbm;
        $text['maxpallet'] = $itf->maxbox_pallet;

       // $text['hpl_avg_weight']=DB::table("packing_detail")->select('net_weight')->where("product",$id)->orderBy('updated','desc')->first()->net_weight;
        return $text;
    }


    public function checkpacking(Request $request)
    {
        $id = $request->itf_id;
        $num_box = $request->num_box;
        $datas = ITFdetailModel::where('itf',$id)->get();
        $count = array();
        $sum = array();
        $name = array();
        $rs = array();
        $rs_re = array();
        $count_re = array();
        $sum_re = array();
        $name_re = array();
        $qty_re = array();
        $checks = json_decode($request->check);
        
        foreach($checks as $k => $c)
        {
            $count[$k] = DB::table('packing_detail as detail')->leftJoin('packing as pack','detail.packing','=','pack.id')
            ->where('detail.product',$k)->count();
            if($count[$k] != 0){
                $sum[$k] = DB::table('packing_detail as detail')->leftJoin('packing as pack','detail.packing','=','pack.id')
                ->where('detail.product',$k)->sum('detail.balance');

                $query = DB::table('products')->where('id',$k)->first();
                $name[$k] = $query->name;

                
            }else{
                $sum[$k] = 0;
                $query = DB::table('products')->where('id',$k)->first();
                $name[$k] = $query->name;
            }
            if($sum[$k])
            {
                $rs[$k] = 'true';
            }else{
                $rs[$k] = 'false';
            }
            $rs[$k] = 'true';
            foreach($datas as $key => $data)
            {
                if($data->type != 'ean'){
                    $count_re[$key] = DB::table('receiving as re')->leftJoin('imports as im','re.import','=','im.id')
                    ->where('re.item',$data->item)->count();
                    if($count_re[$key] != 0){
                        $sum_re[$key] = DB::table('receiving as re')->leftJoin('imports as im','re.import','=','im.id')
                        ->where('re.item',$data->item)->sum('re.balance');
                        $query = DB::table('items')->where('id',$data->item)->first();
                        $name_re[$key] = $query->name_th;
                        $num_qty = ITFdetailModel::where('itf',$id)->where('type','!=','ean')->first();
                        $qty_re[$key] = $num_qty->qty;
                    }else{
                        $sum_re[$key] = 0;
                        $query = DB::table('items')->where('id',$data->item)->first();
                        $name_re[$key] = $query->name_th;
                        $num_qty = ITFdetailModel::where('itf',$id)->where('type','!=','ean')->first();
                        $qty_re[$key] = $num_qty->qty;
                    }
                    if($sum_re[$key]){
                        $rs_re[$k] = 'true';
                    }else{
                        $rs_re[$k] = 'false';
                        break;
                    }
                }
            }
        }
        $text['rs'] = $rs;
        $text['rs_re'] = $rs_re;
        $text['count'] = $count;
        $text['sum'] = $sum;
        $text['name'] = $name;
        $text['count_re'] = $count_re;
        $text['sum_re'] = $sum_re;
        $text['name_re'] = $name_re;
        $text['qty_re'] = $qty_re;
        return $text;
    }

    public function getCost(Request $request)
    {
        $id = $request->id;
        $num_box = $request->num_box;
        $datas = ITFdetailModel::where('itf',$id)->get();
        $iftNW = ITFModel::where('id',$id)->first()->net_weight;


        $pd_id = array();
        $c = array();
        $s = array();
        $sum_c = 0;
        $sum_s = 0;
        $ean_cost=0;
        $pack_cost=0;
        $new_net_weight=0;
        foreach($datas as $key => $data)
        {
            if($data->type == 'ean')
            {
				$ean_id=$data->item;
                $first = DB::table('packing_detail as detail')->select('pack.id','detail.ean_uc as cost_ean','detail.balance','detail.ean_uc','detail.product_weight','detail.net_weight')
                ->leftJoin('packing as pack','detail.packing','=','pack.id')
                ->where('detail.product',$data->item)->orderBy('pack.created','desc')->first();

                if (!$first) {
                    $itf = ITFModel::where('id',$data->itf)->first();
                    $error = [
                        "message" 	=> "No Packing Detail For item number ".$key . " (".$itf->name.")",
                        "exception" 	=> "SiamException",
                        "line" 		=>  __LINE__,
                        "file" 		=> __FILE__
                    ];
           	    return response()->json($error)->setStatusCode(400);
                }
                
               
                $qty = floatval($num_box)*floatval($data->qty);


				$cost = floatval($first->cost_ean)*$data->qty;
					  
				$avg=AverageModel::where(['ean_id'=>$data->item])->orderby("id",'desc');
                     if($avg->count()>0)
                     {
                        $cost=$avg->first()->average;
                     }
                
        
                    $cal = floatval($first->balance)-floatval($qty);
                  
                    $net_weight = floatval($first->net_weight);
                    $pd_id[$key][0]['id'] = $first->id;
                    $pd_id[$key][0]['type'] = 'itf';
                    $pd_id[$key][0]['balance'] = $qty;
                    array_push($c,$cost);
                    $sum_c = array_sum($c);
                    $ean_cost+=$cost;
                    $new_net_weight+=$net_weight/1000*$data->qty;
               
              
            }
			else
			{
                $qty = floatval($num_box)*floatval($data->qty);
                $re_first = DB::table('receiving as re')->select('re.id','im.price','re.balance','re.type')
                ->leftJoin('imports as im','re.import','=','im.id')
                ->where('re.item',$data->item)->orderBy('re.receive_date','desc')->first();
         
                    $cal = floatval($re_first->balance)-floatval($qty);
             
                    $cost = floatval($re_first->price)*$data->qty;
                    $pd_id[$key][0]['id'] = $re_first->id;
                    $pd_id[$key][0]['type'] = $re_first->type;
                    $pd_id[$key][0]['balance'] = $qty;
                    array_push($s,$cost);
                    $sum_s = array_sum($s);

                     $pack_cost+=$cost;
       
    
            }
        }
			
		$ean_ppkg=SetupModel::where(['type'=>'item','product'=>$ean_id])->sum('avg_weight'); 
        $text['pd_id'] = $pd_id;
        $text['ean_cost'] = round(($ean_cost*$new_net_weight)*$ean_ppkg,4);
   
        $text['new_net_weight'] = $new_net_weight;
        $text['pack_cost'] = $pack_cost;
        $text['iftNW'] = $iftNW;
        $text['total_cost'] = $total_cost=round((($ean_cost*$new_net_weight)*$ean_ppkg)+$pack_cost,4);
        $text['cost'] = floatval($sum_c)+floatval($sum_s);
        
        
        return $text;
    }

    // public function reCost(Request $request)
    // {
    //     $id = $request->id;
    //     $costs = CalCostModel::where('itf_id',$id)->get();
    //     if($costs)
    //     foreach($costs as $cost){
    //         if($cost->type == 'itf'){
    //             $packing = PackingDetailModel::where('id',$cost->_id)->first();
    //             $qty = $packing->cal_balance+$cost->qty;
    //             $packing->cal_balance = $qty;
    //             $packing->save();
    //         }else{
    //             $receive = ReceiveModel::where('id',$cost->_id)->first();
    //             $qty = $receive->cal_balance+$cost->qty;
    //             $receive->cal_balance = $qty;
    //             $receive->save();
    //         }
    //         $cost->delete();
    //     }
    // }

    public function getClearance(Request $request)
    {
        $id = $request->id;
        $data = ClearanceModel::find($id);
        $text['clearance'] = floatval($data->charge)+floatval($data->certificate)+floatval($data->extras);
        $text['chamber'] = $data->chamber;
        return $text;
    }

    public function compareTransport(Request $request)
    {
        $id = $request->select_clearance;
        $total_nw = floatval($request->total_nw);
        $total_cbm = floatval($request->total_cbm);
        $pallet = floatval($request->pallet);
        $data = ClearanceModel::find($id);
        $tran = TransportModel::where('vendor',$data->vendor)->first();
        
         if(!isset($tran))
            {
                return "na";
            }
        if($pallet == 0){

            if($total_cbm <= $tran->small_max_cbm && $total_nw <= $tran->small_max_weight){
                $price = $tran->small_price;
            }elseif($total_cbm > $tran->small_max_cbm && $total_cbm <= $tran->medium_max_cbm || $total_nw > $tran->small_max_weight && $total_nw <= $tran->medium_max_weight){
                $price = $tran->medium_price;
            }elseif($total_cbm > $tran->medium_max_cbm && $total_cbm <= $tran->large_max_cbm || $total_nw > $tran->medium_max_weight && $total_nw <= $tran->large_max_weight){
                $price = $tran->large_price;
            }elseif($total_cbm > $tran->large_max_cbm && $total_cbm <= $tran->jumbo_max_cbm || $total_nw > $tran->large_max_weight && $total_nw <= $tran->jumbo_max_weight){
                $price = $tran->jumbo_price;
            }

        }else{
            if($pallet <= $tran->small_pallet)
            {
                $price = $tran->small_price;
            }
            elseif($pallet > $tran->small_pallet && $pallet <= $tran->medium_pallet)
            {
                $price = $tran->medium_price;
            }
            elseif($pallet > $tran->medium_pallet && $pallet <= $tran->large_pallet)
            {
                $price = $tran->large_price;
            }
            elseif($pallet > $tran->large_pallet && $pallet <= $tran->jumbo_pallet)
            {
                $price = $tran->jumbo_price;
            }
        }


        if($total_nw <= $tran->small_max_weight)
        {
                $price = $tran->small_price;
        }
        elseif($total_nw > $tran->small_max_weight && $total_nw <= $tran->medium_max_weight)
        {
                $price = $tran->medium_price;
        }
        elseif($total_nw > $tran->medium_max_weight && $total_nw <= $tran->large_max_weight)
        {
                $price = $tran->large_price;
        }
        elseif( $total_nw > $tran->large_max_weight && $total_nw <= $tran->jumbo_max_weight)
        {
                $price = $tran->jumbo_price;
        }



        return $price;
    }

    public function destroyITF(Request $request)
    {
        $oper = OperationModel::where('order_detail_id',$request->id)->delete();
        $data = OrderDetailModel::destroy($request->id);
        if($data){
            return "true";
        }else{
            return "false";
        }
    }

    public function freight_detail(Request $request)
    {
        $id = $request->id;
        $data = OrderModel::find($id);
        $airlines = FreightModel::select('freight.*','line.id as airline_id','line.name','line.airline_code')->leftJoin('airline as line','freight.airline','=','line.id')->where(['freight.status'=>'on','destination'=>$data->airport_id])->get();
        $text['airline'] = '<option value="">Select airline</option>';
        foreach($airlines as $line)
        {
            if($line->airline_id == $data->airline_id){ 
            $selected = 'selected';
            $text['airline'].='<option value="'.$line->airline_id.'" '.$selected.'>'.$line->name.' ['.$line->airline_code.']'.'</option>';
            }
        }
        $text['awb_no'] = $data->awb_no;
        $text['freight_detail'] = $data->freight_detail;
        $text['ship_date'] = $data->ship_date;
        $text['etd'] = $data->etd;
        $text['eta'] = $data->eta;
        $text['load_date'] = $data->load_date;
        $text['load_time'] = $data->load_time;
        $text['po_number'] = $data->po_number;
        $text['total_package'] = $data->total_package;
        $text['tt_ref'] = $data->tt_ref;
        return $text;
    }

    public function update_freight_detail(Request $request)
    {
        $id = $request->id;
        $order = OrderModel::find($id);
        $order->awb_no = $request->awb_no;
        $order->freight_detail = $request->freight_detail;
        $order->ship_date = $request->ship_date;
        $order->airline_id = $request->airline;
        $order->etd = $request->etd;
        $order->eta = $request->eta;
        $order->load_date = $request->load_date;
        $order->load_time = $request->load_time;
        $order->po_number = $request->po_number;
        $order->total_package = $request->total_package;
        $order->tt_ref = $request->tt_ref;
        if($order->save()){
            return view("$this->prefix.alert.sweet.success",['url'=>url("$this->folder")]);
        }else{
            return view("$this->prefix.alert.sweet.error",['url'=>$request->fullUrl()]);
        }
    }

    public function check_freight_detail(Request $request)
    {
        $id = $request->id;
        $data = OrderModel::find($id);
        if($data->freight_detail != ''){
            return 'true';
        }else{
            return 'false';
        }
    }

    public function confirm(Request $request)
    {
        $id = $request->id;
        $order = OrderModel::find($id);
       
        $data_detail = OperationModel::select('detail.*','operation.box','operation.packaging','operation.adjust','operation.new_gw','operation.packing_weight','operation.over_nw','operation.of_pallets')
        ->leftJoin('orders_detail as detail','operation.order_detail_id','=','detail.id')->where('operation.order_id',$id)->get();
        
        $countP=DB::table('operation')->where('order_id',$id)->where('packaging','!=',null)->count();

        if(count($data_detail)!=$countP)
        {
           $or['percent']=($countP/count($data_detail))*100;
           $or['status']=false;
           return response()->json($or);
        }
		$oldInvoice = InvoiceModel::where('order_id',$id)->where('ship_status','restored')->first();
		
		//echo $oldInvoiceNumber; die;
		if($oldInvoice != '')
		{
			$oldInvoiceNumber = $oldInvoice->code;
			$inv_no = $oldInvoiceNumber;
			InvoiceModel::where('id',$oldInvoice->id)->delete();
		}
		else
		{
			$inv_no = OrderModel::getCode();   
		}
		
		
         //$inv_no = OrderModel::getCode();   
        $invoice = new InvoiceModel;

        OrderModel::where(['id'=>$id])->update(['inv_no'=>$inv_no]);
        $invoice->code = $inv_no;
        $invoice->order_id = $id;
        $invoice->user_id = Auth::user()->id;
        $invoice->client_id = $order->client_id;
        $invoice->shipto_id = $order->shipto_id;
        $invoice->airport_id = $order->airport_id;
        $invoice->airline_id = $order->airline_id;
        $invoice->clearance_id = $order->clearance_id;
        $invoice->select_pallet = $order->select_pallet;
        $invoice->select_chamber = $order->select_chamber;
        $invoice->currency_id = $order->currency_id;
        $invoice->created = date('Y-m-d H:i:s');
        $invoice->ship_status = 'pending';
        $invoice->ex_rate = $order->ex_rate;
        $invoice->markup_rate = $order->markup_rate;
        $invoice->rebate = $order->rebate;
        $invoice->ship_date = $order->ship_date;
        $invoice->clearance = $order->clearance;
        $invoice->chamber = $order->chamber;
        $invoice->clearance_price = $order->clearance_price;
        $invoice->transport = $order->transport;
        $invoice->transport_price = $order->transport_price;
        // $invoice->total_box = $order->total_box;
        // $invoice->total_nw = $order->total_nw;
        // $invoice->total_gw = $order->total_gw;
        $invoice->total_cbm = $order->total_cbm;
        $invoice->palletized = $order->palletized;
        $invoice->palletized_price = $order->palletized_price;
        $invoice->total_freight = $order->total_freight;
        //$invoice->total_package = $order->total_package;
        $invoice->awb_no = $order->awb_no;
        $invoice->po_number = $order->po_number;
        $invoice->freight_detail = $order->freight_detail;
        $invoice->etd = $order->etd;
        $invoice->eta = $order->eta;
        $invoice->load_date = $order->load_date;
        $invoice->load_time = $order->load_time;
        $invoice->tt_ref = $order->tt_ref;

        $invoice->total_cnf = $order->total_cnf;
        $invoice->markup_rateCal = $order->markup_rateCal;
        $invoice->freights = $order->freights;

       

        $total_gw=0;
        $total_nw=0;

        $total_pallets=0;
        $complete_pallets=0;
        $total_pallet_cost=0;
        $total_pallet_weight=0;
        $pallet_cbm=0;
        $total_box=0;
        $total_package=0;

        foreach($data_detail as $pt) // for calculations
        {
            $total_pallets+=$pt->of_pallets;
            $total_box+=$pt->box;
            $total_package+=$pt->packaging;

        }
        $invoice->total_package= $total_package;

        $complete_pallets=ceil($total_pallets);
        $pallets=DB::table('items')->where(['type'=>'pallets'])->first();
        $total_pallet_cost=$complete_pallets*$pallets->cost;
        $total_pallet_weight=$complete_pallets*$pallets->weight;

          

        $pallet_cbm=$complete_pallets*$pallets->volume;
        $invoice->total_pallets = $total_pallets;
        $invoice->complete_pallets = $complete_pallets;
        $invoice->total_pallet_cost = $total_pallet_cost;
        $invoice->total_pallet_weight = $total_pallet_weight;
        $invoice->pallet_cbm = $pallet_cbm;
        $invoice->total_box = $total_box;

        $invoice->save();
  
        foreach($data_detail as $index => $detail)
        {
            $inv_detail = new InvoiceDetailModel;
            $c = array();
            $s = array();
            $sum = 0;
            $sum_s = 0;
            $fob = 0;
            $total_all_cost = 0;
           
            $unitcount_id=$detail->unitcount_id;
    
            
            $hpl_avg_weight=DB::table("packing_detail")->select('net_weight')->where("product",$detail->ean_id)->orderBy('updated','desc')->first()->net_weight;
             $box= $detail->box;
          
            $net_weightNew = $detail->over_nw/$box;
    
          
    
            $netWeight=$detail->over_nw;



      

    
            $unit_price = $detail->unit_price;
            $order_price =  $detail->itf_fx_price/$detail->net_weightNew;
            $profit =$detail->profit;
           $ean_ppkg=SetupModel::where(['type'=>'item','product'=>$detail->ean_id])->sum('avg_weight');  /* avg_weight is ean_ppk in setup table*/
         
            if($unitcount_id=='5')//box
            {
                $qty=$box;
                $fx_price=$order_price*$net_weightNew;
            }
            if($unitcount_id=='2')//kg
            {
                $qty=$netWeight;
                 $fx_price=$order_price;
            }
            if($unitcount_id=='1')//pc
            {
                $qty=$netWeight*$ean_ppkg;//itf_qty
    
                  $fx_price=$order_price/$detail->ean_ppITF;
            }


            $box_weight=($detail->new_gw/$detail->box); //done
            $pallet_weight=($total_pallet_weight/$total_box); //done
            $total_weight=$box_weight;
            if($order->select_pallet=='yes')
            {
                 $total_weight=($box_weight+$pallet_weight);  //done with pallets weight

            }
            $gw_weightc=$total_weight*$detail->box;

           

            $inv_detail->box_weight = $box_weight;
            $inv_detail->pallet_weight = $pallet_weight;
            $inv_detail->total_weight = $total_weight;

            
            $average=AverageModel::where(['order_id'=>$id,'ean_id'=>$detail->ean_id])->first()->average;
        
            $ean_cost= ($average*$net_weightNew)*$ean_ppkg;
 
            $inv_detail->ean_cost = $ean_cost;
            $inv_detail->ean_ppkg = $ean_ppkg;
            $box_qty=ITFdetailModel::where(['itf'=>$detail->itf_id,'type'=>"boxes"])->sum("qty");
          

        

            $sticker_qty=ITFdetailModel::where(['itf'=>$detail->itf_id,'type'=>"packaging"])->sum("qty");
           


         
         


             /*box and packing*/
             //echo $detail->itf_id;
              $pk=ITFdetailModel::where(['itf'=>$detail->itf_id])->where("type",'!=',"ean")->get();
              $cost_pk_total=0;
       
              $cost_box_total=0;
             

              foreach($pk as $p)
              {

                if($p->type=="boxes")
                {
                   /* $per_cost = Model::select('transection.*')
                    ->leftJoin('items as it' , 'transection.item_id', '=', 'it.id')
                    ->where(['item_id'=>$p->item])->orderBy('transection.transection_date','asc')->sum('receiving_cost');
                    */
                    $per_cost = Model::select('transection.*')
                    ->leftJoin('items as it' , 'transection.item_id', '=', 'it.id')
                    ->where(['item_id'=>$p->item])->orderBy('transection.transection_date','desc')->first();
                    
                    //echo "<pre>"; print_r($per_cost->receiving_cost);
		if(!$per_cost){ throw new \Exception($p->item);}
                    $per_cost1 = $per_cost->receiving_cost;
                    
                    //$cost_box_total+=($per_cost*$p->qty);
                    $cost_box_total+=($per_cost1*$p->qty);
                }
                else
                {
                     /*$per_cost = Model::select('transection.*')
                    ->leftJoin('items as it' , 'transection.item_id', '=', 'it.id')
                    ->where(['item_id'=>$p->item])->orderBy('transection.transection_date','asc')->sum('receiving_cost');*/
                    
                    $per_cost = Model::select('transection.*')
                    ->leftJoin('items as it' , 'transection.item_id', '=', 'it.id')
                    ->where(['item_id'=>$p->item])->orderBy('transection.transection_date','desc')->first();
		if(!$per_cost){ throw new \Exception(print_r($p->item, true));}
                    $per_cost1 = $per_cost->receiving_cost;
                    //$cost_pk_total+=($per_cost*$p->qty);
                    $cost_pk_total+=($per_cost1*$p->qty);

                }

              }

              $sticker_cost=$cost_pk_total;
              $box_cost=$cost_box_total;
             /*box and packing end*/
           
            $total_itf_cost=$ean_cost+$cost_pk_total+$cost_box_total;
          
            $inv_detail->box_cost = $box_cost;
            $inv_detail->sticker_cost = $sticker_cost;
            $ex_rate=$order->ex_rate;
            $freights=$order->freights;
            $itf_cal_selling=($fx_price*$qty/$detail->box)*$ex_rate;
            $itf_freight_rate=round($total_weight*$freights,5);
            $freight=round($itf_freight_rate*$detail->box,5);
            $fob=round(($fx_price*$qty*$ex_rate)-$freight,5);
           

            // echo"total_itf_cost $total_itf_cost  ean_cost=$ean_cost  sticker_cost=$cost_pk_total box_cost=$cost_box_total ";
             //die;

            //sticker_cost and box_cost;  both cost are same in all table
            


          
            $inv_detail->order_detail_id = $detail->id;
            $inv_detail->itf_freight_rate = $itf_freight_rate;
            $inv_detail->freight = $freight;
            $inv_detail->fob = $fob;
   


            $inv_detail->net_weightNew_order = $detail->net_weightNew;
            $inv_detail->invoice_id = $invoice->id;
            $inv_detail->itf_id = $detail->itf_id;
            $inv_detail->qty = $qty;
            $inv_detail->unitcount_id = $detail->unitcount_id;
            $inv_detail->ean_id = $detail->ean_id;
            $inv_detail->ean_qty = $detail->ean_qty;
            //$inv_detail->net_weight = $detail->net_weight;
            $inv_detail->net_weight = $netWeight;
            $inv_detail->new_weight = $detail->new_weight;
            $inv_detail->maxcbm = $detail->maxcbm;
            $inv_detail->maxpallet = $detail->maxpallet;
            $inv_detail->number_box = $detail->box;
            $inv_detail->box_of_order = $detail->number_box;
            $inv_detail->nw = $netWeight;
           // $inv_detail->gw_weight = $detail->gw_weight;
            $inv_detail->gw_weight = $gw_weightc;
            $inv_detail->cbm = $detail->cbm;
            $inv_detail->pallet = $detail->pallet;
            $inv_detail->price_allocation = $detail->price_allocation;
            $inv_detail->price_pallet_unit = $detail->price_pallet_unit;
            $inv_detail->itf_pallet_price = $detail->itf_pallet_price;
            $inv_detail->itf_clearance_price = $detail->itf_clearance_price;
            $inv_detail->itf_transport_price = $detail->itf_transport_price;
           /* $inv_detail->itf_cost_price = $itf_cost_price;*/
            $inv_detail->itf_cost_price = $detail->itf_cost_price;
            $inv_detail->order_price = $order_price;
          
            $inv_detail->total_itf_cost = $total_itf_cost;
            $inv_detail->unit_price = $unit_price;
            $inv_detail->profit = $profit;
         
            $inv_detail->created = date('Y-m-d H:i:s');


            

            
                
                $inv_detail->profit2 = $detail->profit2;
                $inv_detail->net_weightNew = $net_weightNew;
                $inv_detail->itf_GW = $detail->new_gw;
                $inv_detail->ean_ppITF = $detail->ean_ppITF;
                $inv_detail->itfQty = $detail->itfQty;
                $inv_detail->net_weight2 = $detail->net_weight2;
                $inv_detail->hpl_avg_weight = $detail->hpl_avg_weight;
                $inv_detail->box_pallet = $detail->box_pallet;
                
                $inv_detail->itf_cal_selling = $itf_cal_selling;

                $inv_detail->fx_price = $fx_price;
                $inv_detail->itf_fx_price = $detail->itf_fx_price;

            $inv_detail->save();
            $fob += $unit_price*$order->ex_rate*$detail->qty;
            $total_all_cost += $total_itf_cost;
            $total_gw += $detail->new_gw;
            $total_nw += $netWeight;
           
        }
        $total_pro_before_rebate = $order->total_pro_before_rebate;
        $total_pro_after_rebate = $order->total_pro_after_rebate;
        $total_fob = $order->total_fob;
        $total_pro_percent = $order->total_pro_percent;
        
         $invoice->total_fob=$total_fob;
        $invoice->total_pro_before_rebate = $total_pro_before_rebate;
        $invoice->total_pro_after_rebate = $total_pro_after_rebate;
        $invoice->total_pro_percent = $total_pro_percent;
        $invoice->total_gw = $total_gw;
        $invoice->total_nw = $total_nw;
        $invoice->total_box = $total_box;
        $invoice->AWB_GW = $total_gw;
        $invoice->save();

        $order->status = 'confirm';
        if($order->save()){
               $or['status']=true;
           return response()->json($or);
        }else{
           
               $or['status']=false;
           return response()->json($or);
        }
    }
 public function getTruck(Request $request)
    {
        $id = $request->select_clearance;
        $total_gw = floatval($request->total_gw);
      
        $pallet = floatval($request->pallet);
        $data = ClearanceModel::find($id);
        $tran = TransportModel::where('vendor',$data->vendor)->first();
        $price=0;

//echo $total_gw;
//echo "<pre>"; print_r($data); 
//echo "<pre>"; print_r($tran); die();

         /*if($total_gw>=0&&$total_gw<=2000)
            {
                $price = $tran->small_price;
            }
             else if($total_gw>2000&&$total_gw<=6000)
            {

            
                $price = $tran->medium_price;
            }
             else if($total_gw>6000&&$total_gw<=8000)
            {
                $price = $tran->large_price;
            }

            else
            {
                 $price = $tran->jumbo_price; 
            }*/

            if($total_gw>=0&&$total_gw<=$tran->small_max_weight)
            {
                $price = $tran->small_price;
            }
             else if($total_gw>$tran->small_max_weight&&$total_gw<=$tran->medium_max_weight)
            {

            
                $price = $tran->medium_price;
            }
             else if($total_gw>$tran->medium_max_weight&&$total_gw<=$tran->large_max_weight)
            {
                $price = $tran->large_price;
            }

            else
            {
                 $price = $tran->jumbo_price; 
            }

       //echo $price; die();
        return $price;
    }


    public function getCostC($id,$num_box)
    {
       
        $datas = ITFdetailModel::where('itf',$id)->get();
        $iftNW = ITFModel::where('id',$id)->first()->net_weight;

        $pd_id = array();
        $c = array();
        $s = array();
        $sum_c = 0;
        $sum_s = 0;
        $ean_cost=0;
        $pack_cost=0;
        $new_net_weight=0;
        foreach($datas as $key => $data)
        {
            if($data->type == 'ean')
            {
                $first = DB::table('packing_detail as detail')->select('pack.id','detail.ean_uc as cost_ean','detail.balance','detail.ean_uc','detail.product_weight','detail.net_weight')
                ->leftJoin('packing as pack','detail.packing','=','pack.id')
                ->where('detail.product',$data->item)->orderBy('pack.created','desc')->first();
                
                $qty = floatval($num_box)*floatval($data->qty);

                    $cal = floatval($first->balance)-floatval($qty);
                 
                    $cost = floatval($first->ean_uc)*floatval($iftNW);
                    $cost = floatval($first->cost_ean);
                    $net_weight = floatval($first->net_weight);
                    $pd_id[$key][0]['id'] = $first->id;
                    $pd_id[$key][0]['type'] = 'itf';
                    $pd_id[$key][0]['balance'] = $qty;
                    array_push($c,$cost);
                    $sum_c = array_sum($c);
                    $ean_cost+=$cost;
                    $new_net_weight+=$net_weight/1000*$data->qty;
               
                
            }else{
                $qty = floatval($num_box)*floatval($data->qty);
                $re_first = DB::table('receiving as re')->select('re.id','im.price','re.balance','re.type')
                ->leftJoin('imports as im','re.import','=','im.id')
                ->where('re.item',$data->item)->orderBy('re.receive_date','desc')->first();
         
                    $cal = floatval($re_first->balance)-floatval($qty);
               
                    $cost = floatval($re_first->price);
                    $pd_id[$key][0]['id'] = $re_first->id;
                    $pd_id[$key][0]['type'] = $re_first->type;
                    $pd_id[$key][0]['balance'] = $qty;
                    array_push($s,$cost);
                    $sum_s = array_sum($s);

                     $pack_cost+=$cost;
               
            }

        }
        $text['pd_id'] = $pd_id;
        $text['ean_cost'] = round($ean_cost*$new_net_weight,4);
        $text['new_net_weight'] = $new_net_weight;
        $text['pack_cost'] = $pack_cost;
        $text['iftNW'] = $iftNW;
        $text['total_cost'] = round($ean_cost*$new_net_weight,4)+$pack_cost;
        $text['cost'] = floatval($sum_c)+floatval($sum_s);
        
        return $text;
    }
	
	public function cancel_order(Request $request)
	{
		$id = $request->id;
		
		$url="/$this->folder";
		if($request->url!=null)
		{
			$url="/operation";
		}
			
		$isLocked = DB::table('orders')->select('isLocked')->where('id',$id)->first()->isLocked;
		if($isLocked == '0')
		{
			DB::table('orders')->where('id',$id)->update(['status'=>'cancel']);
			

		   return view("$this->prefix/alert/sweet/success",['url'=>url($url),'text'=>'test']);
		}
		else
		{
			echo"<script src='https://mobileandwebsitedevelopment.com/TERP/public/back-end/js/jquery.min.js'></script>
				<script src='https://mobileandwebsitedevelopment.com/TERP//back-end/js/sweetalert2.all.min.js'></script>
	
				<script>
			const url = '".url($url)."';
			$(document).ready(function(){
				  Swal.fire({ 
						title: 'Error!',
						text: 'Sorry you can`t cancel Order until unpack , packed items for this order.!',
						icon: 'error',
						closeOnClickOutside: false,
				  }).then((result) => {
						if(url==''){
							  window.location=window.location.href;
						}else{
							  window.location=url
						}
				  });
			});
			</script>

			";
	
		}
		
	}
    
}
