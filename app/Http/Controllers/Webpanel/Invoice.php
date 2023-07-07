<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
use App\InvoiceCancelModel;
use App\PoCodeModel;

class Invoice extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'invoice';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = InvoiceModel::orderBy('created','desc')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('inv_no','like',"%{$keyword}%");
                // ->orWhere('city','like',"%{$keyword}%")
                // ->orWhere('airport_code','like',"%{$keyword}%");
            });
         $rows1 = $query->get();
        /*if($view=='all'){
            $rows1 = $query->get();
        }else{
            $view = ($request->view)? $view : 10 ;
            $rows1 = $query->paginate($view);
            $rows1->appends(['view' => $request->view,'keyword' => $keyword,'page' => $request->page]);
        }*/
	    $rr=$rows1->merge(InvoiceCancelModel::get());
		//echo "<pre>"; print_r($rr); die;
		//$rows=$rr->sortByDesc('code');
		$rows=$rr->sortByDesc('created');
		
		
        return view("$this->prefix.pages.$this->folder.index",[
            'css'=> [
                'public/back-end/css/table-responsive.css',
                'public/back-end/css/select2.min.css',
                '//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css'
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'public/back-end/js/select2.min.js'],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/$this->folder.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/new/invoice-util.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'index',
            'segment' => $this->segment,
            'rows' => $rows,
        ]);
		
		/*$keyword = $request->keyword;
        $view = $request->view;
        $query = InvoiceModel::orderBy('created','desc')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('inv_no','like',"%{$keyword}%");
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
        ]);*/
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
        $data = InvoiceModel::find($id);
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
            'details' => InvoiceDetailModel::where('invoice_id',$data->id)->get(),
            'clients' => ClientModel::where(['status'=>'on','id'=>$data->client_id])->first(),
            'shipto' => ShipToModel::where(['status'=>'on','client'=>$data->client_id])->first(),
            'airports' => AirportModel::where(['status'=>'on','id'=>$data->airport_id])->first(),
            'airlines' => AirlineModel::where(['status'=>'on','id'=>$data->airline_id])->first(),
            'currencys' => CurrencyModel::where(['status'=>'on','id'=>$data->currency_id])->first(),
            'clearance' => ClearanceModel::select('clearance.*','v.name')->leftJoin('vendors as v','clearance.vendor','=','v.id')->where(['clearance.status'=>'on','clearance.id'=>$data->clearance_id])->first(),
            'units' => UnitCountModel::whereIn('id', [1,2,4])->get(),
        ]);
    }
 public function claim($id)
    {
        $data = InvoiceModel::find($id);
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
             
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'claim',
            'segment' => $this->segment,
            'row' => $data,
            'details' => InvoiceDetailModel::where('invoice_id',$data->id)->get(),
            'clients' => ClientModel::where(['status'=>'on','id'=>$data->client_id])->first(),
            'shipto' => ShipToModel::where(['status'=>'on','client'=>$data->client_id])->first(),
            'airports' => AirportModel::where(['status'=>'on','id'=>$data->airport_id])->first(),
            'airlines' => AirlineModel::where(['status'=>'on','id'=>$data->airline_id])->first(),
            'currencys' => CurrencyModel::where(['status'=>'on','id'=>$data->currency_id])->first(),
            'clearance' => ClearanceModel::select('clearance.*','v.name')->leftJoin('vendors as v','clearance.vendor','=','v.id')->where(['clearance.status'=>'on','clearance.id'=>$data->clearance_id])->first(),
            'units' => UnitCountModel::whereIn('id', [1,2])->get(),
            'pall' => DB::table("items")->where('type','pallets')->first(),
        ]);
    }
    public function saveClaim(Request $request)
    {

        $date=date("Y-m-d H:i:s");
        $claim['user_id']=$request->user;
        
        $claim['created_at']=$date;
        $code=str_replace("INV-","", $request->code);
        $claim['claim_code']=$this->getClCode();

        $claim['total_price']=$request->total_price;
        $claim['order_id']=$request->order_id;
        $claim['invoice_id']=$request->invoice_id;
        $claim['claim_qty']=$request->claim_qty;
        $claim['currency']=$request->currency;


        if($request->claim_qty=="")
        {
            return view("$this->prefix/alert/sweet/error",['url'=>url("/$this->folder")]);
        }
         if(count($request->quantity)==0)
        {
            return view("$this->prefix/alert/sweet/error",['url'=>url("/$this->folder")]);
        }

         if(count($request->quantity)==0)
        {
            return view("$this->prefix/alert/sweet/error",['url'=>url("/$this->folder")]);
        }
        $yes="0";
        foreach($request->quantity as $key =>$q)
        {
            if($q>=1)
            {
                 $yes="1";
                break;
            }
        }

        if($yes=="0")
        {

            return view("$this->prefix/alert/sweet/error",['url'=>url("/$this->folder")]);
        }
          $images = array();
         $claim['image']="na";
          if($file = $request->file('image'))
          {
              foreach($file as $file)
              {
                  $image_name = $claim['claim_code']."-".rand(100,999);
                  $ext = strtolower($file->getClientOriginalExtension());
                  $image_full_name = $image_name.'.'.$ext;
                  $uploade_path = 'public/claim/';
                  $image_url = $uploade_path.$image_full_name;
                  $file->move($uploade_path,$image_full_name);
                  $images[] = $image_url;
              }

              if(count($images)>0)
              {
                $claim['image']=implode(',', $images);
              }
          }

       

        $claim_id=DB::table("claims")->insertGetId($claim);


        $image="na";
        foreach($request->quantity as $key =>$q)
        {
            if($q==0)
            continue;
            $claim_details['claim_id']=$claim_id;
            $claim_details['created_at']=$date;
            $claim_details['invoice_id']=$request->invoice_id;
            $claim_details['user_id']=$request->user_id;
            $claim_details['unit_price']=$q;
            $claim_details['line_total']=$request->lineTotal[$key];
            $claim_details['claim_unit']=$request->unitcount[$key];
            $claim_details['claim_qty']=$q;
          
            $claim_details['itf_id']=$request->itf_id[$key];
            DB::table("claim_detail")->insertGetId($claim_details);
            DB::table("invoices_detail")->where('invoice_id',$request->invoice_id)->where('itf_id',$request->itf_id[$key])->update(['claim'=>$claim_id]);


        }
      
        return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
       
            //return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
      
    }

    public function edit($id)
    {
        $data = InvoiceModel::find($id);
    //echo "<pre>"; print_r($data); die;
  
        
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
                ["type"=>"text/javascript","src"=>"public/back-end/build/order_invoice.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'edit',
            'segment' => $this->segment,InvoiceDetailModel::where('invoice_id',$data->id)->get(),
            'row' => $data,
            'details' =>InvoiceDetailModel::select(['invoices_detail.*','od.number_box as box_of_order'])->join('orders_detail as od','od.id','=','invoices_detail.order_detail_id')->where('invoice_id',$data->id)->get(),
            'clients' => ClientModel::where('status','on')->get(),
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

    public function update_note(Request $request, $id) {
        $invoice = InvoiceModel::find($id);
	$invoice->note = $request->note;
	$invoice->save();
	return $request->note;
    }

    public function note(Request $request, $id) {
        $invoice = InvoiceModel::find($id);
	return $invoice->note;
    }

 public function update(Request $request, $id)
    {
          //echo"<pre>";
         //print_r($request->all());
        // die;
        $order = InvoiceModel::find($id);
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
        $order->total_fob = $request->total_fob;
        $order->total_cnf = str_replace(',', '', $request->total_cnf);
        $order->total_pro_before_rebate = $request->total_pro_before_rebate;
        $order->total_pro_after_rebate = $request->total_pro_after_rebate;
        $order->total_pro_percent = $request->total_pro_percent;
      
        $order->save();
        foreach($request->itf as $key => $val){
            $detail = InvoiceDetailModel::find(@$request->detail_id[$key]);
            if(@$detail->id){
                // $detail->order_id = $order->id;
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
                $detail->gw_weight = $request->itf_GW[$key];
                $detail->itf_GW = $request->itf_GW[$key];
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
                $detail->save();
            }else{
                $new = new InvoiceDetailModel;
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
                $new->gw_weight = $request->itf_GW[$key];
                $new->itf_GW = $request->itf_GW[$key];
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
                $new->fob = $request->fob[$key];
                $new->created = date('Y-m-d H:i:s');
                $new->updated = date('Y-m-d H:i:s');
                $new->save();

               
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

    public function getTotal(Request $request)
    {
        $id = $request->id;
        $data = InvoiceModel::find($id);
        $text['total_gw'] = $data->total_gw;
        $text['total_package'] = $data->total_package;
        return $text;
    }

    public function editTotal(Request $request)
    {
        $id = $request->id;
        $data = InvoiceModel::find($id);
        $data->total_gw = $request->total_gw;
        $data->total_package = $request->total_package;
        if($data->save()){
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function carry(Request $request)
    {
        $id = $request->id;
        $order_id = $request->order_id;

        $pol=DB::table("pol")->where("order_id",$order_id)->get();
        foreach($pol as $p)
        {
            $u=DB::table("setups")->select(['unit','item','avg_weight'])->where(['product'=>$p->ean_id,'type'=>"item"])->first();
				$po_code=PoCodeModel::code($p->packing_detail_id);
				//$ean_uc=PoCodeModel::ean_uc($p->packing_detail_id);
				$ean_uc=DB::table('transection')->where('code',$po_code)->where('transection_menu','receive')->first()->receiving_cost;
                $this->sold($u->item,(($p->qty*-1)/$u->avg_weight),$u->unit,$p->order_id,$po_code,$ean_uc);

        }
       
        $data = InvoiceModel::find($id);
        $data->ship_status = 'carry';
        if($data->save()){
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }


    public function sold($item_id,$qty,$unit,$order_id,$po_code,$uc)
    {
        $code=InvoiceModel::where('order_id',$order_id)->first()->code;
        $data['item_id']=$item_id;
        $data['type']="item";
        $data['qty']=$qty;
        $data['unit']=$unit;
        $data['transection_type']=$code;
        $data['code']=$po_code;
     
        $data['transection_menu']="invoice";
        $data['transection_date']=date("Y-m-d H:i:s");
       
       $receiving_cost=$uc;
        $data['receiving_cost']=$receiving_cost;
        $data['transactionValue']=$receiving_cost*$qty;
        
        $data['waste_qty']=$qty;            
        $data['good_qty']=0;         
    

        $in=DB::table('transection')->insertGetId($data);
       
    }

    public function shipping(Request $request)
    {
        $id = $request->id;
        $data = InvoiceModel::find($id);
        $data->ship_status = 'shipping';
        if($data->save()){
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }

    public static function getClCode()
    {
        // $maxId = DB::table('pos')->orderBy('code','desc')->first();
        // $maxId = DB::table('pos')->whereNull('code')->orderBy('id','desc')->first();
        $maxBC = DB::table('claims')->select('claim_code')->orderBy('claim_code','desc')->orderBy('created_at','desc')->limit(1)->first();
        $prefix = 'CL-';

        if($maxBC != null)
        {

            $cut = substr("$maxBC->claim_code",3); //2020030001
            $cut2 = substr("$cut",0,-4); //202003
         
            if($cut2 == date("Ym"))
            {
                $chk = substr("$maxBC->claim_code",-3); //0001

                if(intval($chk) >= 1)
                {
                    $chk = intval($chk)+1;
                    $gen = substr("0000$chk",-3);
                }
                else
                {
                    $gen = substr("00001",-3);
                }

            }
            else
            {
                $gen = substr("00001",-3);
            }
        }
        else
        {
            $gen = substr("00001",-3);
        }
        $nextId = $prefix.date("Ym").$gen;
     // echo" <br> $cut";
     //     die;
        return $nextId;
    }

    public function claims(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query =DB::table("claims");

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
            'page' => 'claim_list',
            'segment' => $this->segment,
            'rows' => $rows,
        ]);
    }

     public function claim_detail($id)
    {
         $data =DB::table("claims")->where('id',$id)->first();
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
               
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'claim_view',
            'segment' => $this->segment,
            'row' => $data,
            'details' => DB::table("claim_detail")->where('id',$id)->get(),
           
        ]);
    }

    public function claim_status(Request $r)
    {
        $status=$r->status;
        $id=$r->token;

        if($status=='2')
        {
            if($r->remarks)
            {
               DB::table("claims")->where('id',$id)->update(["status"=>$status,'remarks'=>$r->remarks,'updated_at'=>date("Y-m-d")]);
                
            }
        }
        else
        {
              DB::table("claims")->where('id',$id)->update(["status"=>$status,'updated_at'=>date("Y-m-d")]);
               
        
        }

        return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder/claims")]);
 
    }
      public function myClaims(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query =DB::table("claims");

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
            'page' => 'my-claim_list',
            'segment' => $this->segment,
            'rows' => $rows,
        ]);
    }

     public function myClaim_detail($id)
    {
         $data =DB::table("claims")->where('id',$id)->first();
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
               
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'my-claim_view',
            'segment' => $this->segment,
            'row' => $data,
            'details' => DB::table("claim_detail")->where('id',$id)->get(),
           
        ]);
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
        //return $request->total_gw;
        //die();

        if($request->total_gw)
        {
            $gw = floatval($request->total_gw);
            $destination = $request->destination;
            $airline = $request->airline;
            $clear = ClearanceModel::where('id',$request->clear)->first();

           
            $data = FreightModel::where(['vendor'=>$clear->vendor,'destination'=>$destination,'airline'=>$airline])->first();

// echo"<pre> 'vendor'=>$clear->vendor,'destination'=>$destination,'airline'=>$airline";
            if(!isset($data))
            {
                return "na";
            }
       //      print_r($data);
       // echo"<br>";
       // die;
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


            /*nago*/
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
                $text['rate'] = $data->nego_rate_1000;
            }
            elseif($gw >= 2000 && floatval($data->nego_rate_2000) != 0)
            {
                $text['nego_rate'] = $data->nego_rate_2000;
            }
            /*nago*/


          

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
            $text['cost_ean']=DB::table("packing_detail")->select('cost_ean')->where("product",$data->item)->orderBy('updated','desc')->first()->cost_ean;
            $text['hpl_avg_weight']=DB::table("packing_detail")->select('net_weight')->where("product",$data->item)->orderBy('updated','desc')->first()->net_weight;
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
 //$text['hpl_avg_weight']=DB::table("packing_detail")->select('net_weight')->where("product",$data->item)->orderBy('updated','desc')->first()->net_weight;
        
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
            if($count[$k] != 0)
            {
             
                $sum[$k] = DB::table('packing_detail as detail')->leftJoin('packing as pack','detail.packing','=','pack.id')
                ->where('detail.product',$k)->sum('detail.balance');
                $query = DB::table('products')->where('id',$k)->first();
                $name[$k] = $query->name;
            }
            else
            {
                
             
                $sum[$k] = 0;
                $query = DB::table('products')->where('id',$k)->first();
                $name[$k] = $query->name;
            }
            
            if($sum[$k])
            {
                $rs[$k] = 'true';
            }
            else
            {
                $rs[$k] = 'false';
            }
            $rs[$k] = 'true';
            foreach($datas as $key => $data)
            {
                if($data->type != 'ean')
                {
                    // echo $data->item;
                    // die;
                    $count_re[$key] = DB::table('receiving as re')->leftJoin('imports as im','re.import','=','im.id')
                    ->where('re.item',$data->item)->count();
                    if($count_re[$key] != 0)
                    {
                        $sum_re[$key] = DB::table('receiving as re')->leftJoin('imports as im','re.import','=','im.id')
                        ->where('re.item',$data->item)->sum('re.balance');
                        $query = DB::table('items')->where('id',$data->item)->first();
                        $name_re[$key] = $query->name_th;
                        $num_qty = ITFdetailModel::where('itf',$id)->where('type','!=','ean')->first();
                        $qty_re[$key] = $num_qty->qty;
                    }
                    else
                    {
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

 // echo"<pre>";
 //            print_r($itf);
 //            die;
     
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



              

                 // print_r($first);
         //die;
                    $cal = floatval($first->balance)-floatval($qty);
                    //$cost = floatval($qty)*floatval($first->ean_cost);
                    // echo floatval($data->ean_uc);
                    // die;
                   // $cost = floatval($first->ean_uc)*floatval($iftNW);
                    $cost = floatval($first->cost_ean);
                    $net_weight = floatval($first->net_weight);
                    $pd_id[$key][0]['id'] = $first->id;
                    $pd_id[$key][0]['type'] = 'itf';
                    $pd_id[$key][0]['balance'] = $qty;
                    array_push($c,$cost);
                    $sum_c = array_sum($c);
                    $ean_cost+=$cost;
                    $new_net_weight+=$net_weight/1000*$data->qty;
                // }
                
            }else{
                $qty = floatval($num_box)*floatval($data->qty);
                $re_first = DB::table('receiving as re')->select('re.id','im.price','re.balance','re.type')
                ->leftJoin('imports as im','re.import','=','im.id')
                ->where('re.item',$data->item)->orderBy('re.receive_date','desc')->first();
         
                    $cal = floatval($re_first->balance)-floatval($qty);
                  // $cost = floatval($qty)*floatval($re_first->price);
                    $cost = floatval($re_first->price);
                    $pd_id[$key][0]['id'] = $re_first->id;
                    $pd_id[$key][0]['type'] = $re_first->type;
                    $pd_id[$key][0]['balance'] = $qty;
                    array_push($s,$cost);
                    $sum_s = array_sum($s);

                     $pack_cost+=$cost;
                // }
               
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

    public function getClearance(Request $request)
    {
        $id = $request->id;
        $data = ClearanceModel::find($id);
        $text['clearance'] = floatval($data->charge)+floatval($data->certificate)+floatval($data->extras);
        $text['chamber'] = $data->chamber;
        $text['charge'] = $data->charge;
        $text['certificate'] = $data->certificate;
        $text['extras'] = $data->extras;
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
        // print_r($tran);
        // die;
        if($pallet == 0)
        {

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



    public function getTruck(Request $request)
    {
        $id = $request->select_clearance;
        $total_gw = floatval($request->total_gw);
      
        $pallet = floatval($request->pallet);
        $data = ClearanceModel::find($id);
        $tran = TransportModel::where('vendor',$data->vendor)->first();
        $price=0;

//echo$total_gw;

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



       
        return $price;
    }

    function updateGW(Request $request)
    {
        $id=$request->id;
        $AWB_GW=$request->AWB_GW;

        InvoiceModel::where("id",$id)->update(['AWB_GW'=>$AWB_GW]);

        return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        
    }

    function restore($invoice_id,$row_no)
    {
		if($row_no != 0)
		{
			$order_id=InvoiceModel::where("id",$invoice_id)->first()->order_id;
			//InvoiceModel::where("id",$invoice_id)->delete();
			InvoiceModel::where("id",$invoice_id)->update(['ship_status'=>'restored']);
			InvoiceDetailModel::where("invoice_id",$invoice_id)->delete();
			OrderModel::where("id",$order_id)->update(['status'=>'pending']);
		}
		else
		{
			$order_id=InvoiceModel::where("id",$invoice_id)->first()->order_id;
			InvoiceModel::where("id",$invoice_id)->delete();
			InvoiceDetailModel::where("invoice_id",$invoice_id)->delete();
			OrderModel::where("id",$order_id)->update(['status'=>'pending']);
		}
        return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        
    }
	function restore1(Request $request)
    {
		$inv_id = $request->inv_id;
		$reason = $request->invoice_reason;
		$order_id=InvoiceModel::where("id",$inv_id)->first()->order_id;
		OrderModel::where("id",$order_id)->update(['status'=>'pending']);
		$invoice = InvoiceModel::where("id",$inv_id)->first();
		$invoice_arr = array(
						'order_id' =>$invoice->order_id,
						'code' => $invoice->code,
						'user_id'=> $invoice->user_id,
						'client_id'=> $invoice->client_id,
						'shipto_id'=> $invoice->shipto_id,
						'airport_id'=> $invoice->airport_id,
						'clearance_id'=> $invoice->clearance_id,
						'currency_id'=> $invoice->currency_id,
						'ex_rate'=> $invoice->ex_rate,
						'markup_rate'=> $invoice->markup_rate,
						'rebate'=> $invoice->rebate,
						'select_pallet'=> $invoice->select_pallet,
						'select_chamber'=> $invoice->select_chamber,
						'clearance'=> $invoice->clearance,
						'chamber'=> $invoice->chamber,
						'clearance_price'=> $invoice->clearance_price,
						'transport'=> $invoice->transport,
						'transport_price'=> $invoice->transport_price,
						'total_box'=> $invoice->total_box,
						'total_nw'=> $invoice->total_nw,
						'total_gw'=> $invoice->total_gw,
						'total_cbm'=> $invoice->total_cbm,
						'palletized'=> $invoice->palletized,
						'palletized_price'=> $invoice->palletized_price,
						'total_freight'=> $invoice->total_freight,
						'total_fob'=> $invoice->total_fob,
						'total_pro_before_rebate'=> $invoice->total_pro_before_rebate,
						'total_pro_after_rebate'=> $invoice->total_pro_after_rebate,
						'total_pro_percent'=> $invoice->total_pro_percent,
						'total_package'=> $invoice->total_package,
						'ship_status'=> 'Voided',
						'awb_no'=> $invoice->awb_no,
						'po_number'=> $invoice->po_number,
						'freight_detail'=> $invoice->freight_detail,
						'etd'=> $invoice->etd,
						'eta'=> $invoice->eta,
						'load_date'=> $invoice->load_date,
						'load_time'=> $invoice->load_time,
						'ship_date'=> $invoice->ship_date,
						'tt_ref'=> $invoice->tt_ref,
						'created'=> $invoice->created,
						'updated'=> $invoice->updated,
						'total_cnf'=> $invoice->total_cnf,
						'markup_rateCal'=> $invoice->markup_rateCal,
						'freights'=> $invoice->freights,
						'total_pallets'=> $invoice->total_pallets,
						'complete_pallets'=> $invoice->complete_pallets,
						'total_pallet_cost'=> $invoice->total_pallet_cost,
						'total_pallet_weight'=> $invoice->total_pallet_weight,
						'pallet_cbm'=> $invoice->pallet_cbm,
						'AWB_GW'=> $invoice->AWB_GW,
						'reason'=> $reason
						);
		DB::table('invoices_cancel')->insert($invoice_arr);
		$invoice_details = InvoiceDetailModel::where("invoice_id",$inv_id)->get();
		
		foreach($invoice_details as $invoice_detail)
		{
			$invoice_details_arr = array(
						'invoice_id' =>$invoice_detail->invoice_id,
						'itf_id' =>$invoice_detail->itf_id,
						'unitcount_id' =>$invoice_detail->unitcount_id,
						'qty' =>$invoice_detail->qty,
						'ean_id' =>$invoice_detail->ean_id,
						'ean_qty' =>$invoice_detail->ean_qty,
						'maxcbm' =>$invoice_detail->maxcbm,
						'maxpallet' =>$invoice_detail->maxpallet,
						'net_weight' =>$invoice_detail->net_weight,
						'new_weight' =>$invoice_detail->new_weight,
						'itf_GW' =>$invoice_detail->itf_GW,
						'nw' =>$invoice_detail->nw,
						'gw_weight' =>$invoice_detail->gw_weight,
						'cbm' =>$invoice_detail->cbm,
						'pallet' =>$invoice_detail->pallet,
						'price_allocation' =>$invoice_detail->price_allocation,
						'price_pallet_unit' =>$invoice_detail->price_pallet_unit,
						'itf_pallet_price' =>$invoice_detail->itf_pallet_price,
						'itf_clearance_price' =>$invoice_detail->itf_clearance_price,
						'average_lot' =>$invoice_detail->average_lot,
						'itf_transport_price' =>$invoice_detail->itf_transport_price,
						'itf_cost_price' =>$invoice_detail->itf_cost_price,
						'itf_freight_rate' =>$invoice_detail->itf_freight_rate,
						'total_itf_cost' =>$invoice_detail->total_itf_cost,
						'unit_price' =>$invoice_detail->unit_price,
						'profit' =>$invoice_detail->profit,
						'fob' =>$invoice_detail->fob,
						'created' =>$invoice_detail->created,
						'updated' =>$invoice_detail->updated,
						'profit2' =>$invoice_detail->profit2,
						'itf_cal_selling' =>$invoice_detail->itf_cal_selling,
						'ean_ppITF' =>$invoice_detail->ean_ppITF,
						'itfQty' =>$invoice_detail->itfQty,
						'net_weight2' =>$invoice_detail->net_weight2,
						'hpl_avg_weight' =>$invoice_detail->hpl_avg_weight,
						'box_pallet' =>$invoice_detail->box_pallet,
						'itf_fx_price' =>$invoice_detail->itf_fx_price,
						'fx_price' =>$invoice_detail->fx_price,
						'claim' =>$invoice_detail->claim,
						'ean_cost' =>$invoice_detail->ean_cost,
						'order_detail_id' =>$invoice_detail->order_detail_id,
						'box_of_order' =>$invoice_detail->box_of_order,
						'net_weightNew_order' =>$invoice_detail->net_weightNew_order,
						'net_weightNew' =>$invoice_detail->net_weightNew,
						'order_price' =>$invoice_detail->order_price,
						'sticker_cost' =>$invoice_detail->sticker_cost,
						'box_cost' =>$invoice_detail->box_cost,
						'box_weight' =>$invoice_detail->box_weight,
						'total_weight' =>$invoice_detail->total_weight,
						'number_box' =>$invoice_detail->number_box,
						'pallet_weight' =>$invoice_detail->pallet_weight,
						'freight' =>$invoice_detail->freight,
						'ean_ppkg' =>$invoice_detail->ean_ppkg
						
						);
			DB::table('invoices_detail_cancel')->insert($invoice_details_arr);
		}
		InvoiceModel::where("id",$inv_id)->delete();
        InvoiceDetailModel::where("invoice_id",$inv_id)->delete();
		return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
		
        
    }


}
