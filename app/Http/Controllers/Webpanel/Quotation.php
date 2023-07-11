<?php



namespace App\Http\Controllers\Webpanel;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

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

use App\OperationModel;

use App\AverageModel;

use App\SetupModel as SetupModel;

error_reporting(0);



class Quotation extends Controller

{

    protected $prefix = 'back-end';

    protected $segment = 'webpanel';

    protected $folder = 'quotation';



    public function index(Request $request)

    {

        $keyword = $request->keyword;

        $view = $request->view;

        $query = QuotationModel::orderBy('created','desc')

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

                'public/back-end/css/table-responsive.css','public/back-end/css/validate.css',

                'public/back-end/css/select2.min.css'

            ],

            'js' => [

                ['type'=>"text/javascript",'src'=>"public/back-end/js/jquery.min.js",'class'=>"view-script"],

                ["src"=>'public/back-end/js/sweetalert2.all.min.js'],

                ["src"=>'public/back-end/js/select2.min.js'],

                ['src'=>"public/back-end/js/table-dragger.min.js"],

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

        return view("$this->prefix.pages.$this->folder.index",[

            'css'=> [

                'public/back-end/css/table-responsive.css',

                'public/back-end/css/select2.min.css'

            ],

            'js' => [

                ['type'=>"text/javascript",'src'=>"public/back-end/js/jquery.min.js",'class'=>"view-script"],

                ["src"=>'public/back-end/js/select2.min.js'],

                ["src"=>'public/back-end/js/sweetalert2.all.min.js'],

                ['src'=>"public/back-end/js/table-dragger.min.js"],

                ["type"=>"text/javascript","src"=>"public/back-end/build/$this->folder.js"],

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





        



        if(!$request->total_cnf||$request->total_cnf==null||$request->total_cnf=="NaN")

        {

             return view("$this->prefix.alert.sweet.error",['url'=>$request->fullUrl()]);

             



        }

        // echo"<pre>";

        // print_r($request->all());

        // die;

       



        $quotation = new QuotationModel;

        $quotation->code = QuotationModel::getQCode();

        $quotation->user_id = $request->user;

        $quotation->client_id = $request->client;

        $quotation->shipto_id = $request->shipto;

        $quotation->airport_id = $request->airport;

        $quotation->airline_id = $request->airline;

        $quotation->clearance_id = $request->select_clearance;

        $quotation->select_pallet = $request->select_pallet;

        $quotation->select_chamber = $request->select_chamber;

        $quotation->currency_id = $request->currency;

        $quotation->created = date('Y-m-d H:i:s');

        $quotation->updated = date('Y-m-d H:i:s');

        $quotation->status = 'pending';

        $quotation->ex_rate = $request->ex_rate;

        $quotation->markup_rate = $request->markup_rate;

        $quotation->rebate = $request->rebate;

        $quotation->ship_date = $request->ship_date;

        $quotation->clearance = $request->clearance;

        $quotation->chamber = $request->chamber;

        $quotation->clearance_price = $request->clearance_price;

        $quotation->transport = $request->transport;

        $quotation->transport_price = $request->transport_price;

        $quotation->total_box = $request->total_box;

        $quotation->total_nw = $request->total_nw;

        $quotation->total_gw = $request->total_gw;

        $quotation->total_cbm = $request->total_cbm;

        $quotation->palletized = $request->palletized;

        $quotation->palletized_price = $request->palletized_price;

        $quotation->total_freight = $request->total_freight;

        $quotation->total_fob = $request->total_fob;

        $quotation->total_pro_before_rebate = $request->total_pro_before_rebate;

        $quotation->total_pro_after_rebate = $request->total_pro_after_rebate;

        $quotation->total_pro_percent = $request->total_pro_percent;

        $quotation->total_cnf = $request->total_cnf;

        $quotation->markup_rateCal = $request->markup_rateCal;

        $quotation->freights = $request->freights;

        

        $total_fob= floatval(preg_replace("/[^-0-9\.]/","",$quotation->total_fob));

        $total_freight= floatval(preg_replace("/[^-0-9\.]/","",$quotation->total_freight));

     

        $quotation->total_cnf = floatval($total_fob)+floatval($total_freight);



        if($quotation->save())

        {

            for($i=0; $i<count($request->itf); $i++)

            {

                $detail = new QuotationDetailModel;

                $detail->quotation_id = $quotation->id;

                $detail->itf_id = $request->itf[$i];

                $detail->qty = $request->quantity[$i];

                $detail->unitcount_id = $request->unitcount[$i];

                $detail->ean_id = $request->ean_id[$i];

                $detail->ean_qty = $request->ean_qty[$i];

                $detail->net_weight = $request->net_weight[$i];

                $detail->new_weight = $request->new_weight[$i];

                $detail->maxcbm = $request->maxcbm[$i];

                $detail->maxpallet = $request->maxpallet[$i];

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

               /* $detail->total_itf_cost = $request->total_itf_cost[$i];*/

                $detail->total_itf_cost = $request->itotal_cost[$i];

                $detail->unit_price = $request->unit_price[$i];

                $detail->itf_cal_selling = $request->itf_cal_selling[$i];

                $detail->profit = $request->profit[$i];

                $detail->profit2 = $request->profit2[$i];

                $detail->fob = $request->itf_fob[$i];

                $detail->net_weightNew = $request->net_weightNew[$i];

                $detail->itf_GW = $request->itf_GW[$i];

                $detail->ean_ppITF = $request->ean_ppITF[$i];

                $detail->itfQty = $request->itfQty[$i];

                $detail->net_weight2 = $request->net_weight2[$i];

                $detail->hpl_avg_weight = $request->hpl_avg_weight[$i];

                $detail->box_pallet = $request->box_pallet[$i];

                $detail->box_weight = $request->box_weight[$i];

                $detail->fx_price = $request->fixPrice[$i];

                $detail->itf_fx_price = $request->itf_fx_price[$i];



                 $detail->itotal_cost = $request->itotal_cost[$i];

                 $detail->rean_cost = $request->rean_cost[$i];

                 $detail->rclearance = $request->rclearance[$i];

                 $detail->rchamber = $request->rchamber[$i];

                 $detail->rtruck = $request->rtruck[$i];

                 $detail->rpallets = $request->rpallets[$i];

                 $detail->rifreight = $request->rifreight[$i];





                $detail->created = date('Y-m-d H:i:s');

                $detail->updated = date('Y-m-d H:i:s');

                $detail->save();

            }

            

         

            return view("$this->prefix.alert.sweet.success",['url'=>url("$this->folder")]);

        }else{

            return view("$this->prefix.alert.sweet.error",['url'=>$request->fullUrl()]);

        }

    }



    public function show($id)

    {

        $data = QuotationModel::find($id);

        return view("$this->prefix.pages.$this->folder.index",[

            'css'=> [

                'public/back-end/css/table-responsive.css',

                'public/back-end/css/select2.min.css'

            ],

            'js' => [

                ['type'=>"text/javascript",'src'=>"public/back-end/js/jquery.min.js",'class'=>"view-script"],

                ["src"=>'public/back-end/js/select2.min.js'],

                ["src"=>'public/back-end/js/sweetalert2.all.min.js'],

                ['src'=>"public/back-end/js/table-dragger.min.js"],

                ["type"=>"text/javascript","src"=>"public/back-end/build/$this->folder.js"],

            ],

            'prefix' => $this->prefix,

            'folder' => $this->folder,

            'page' => 'view',

            'segment' => $this->segment,

            'row' => $data,

            'details' => QuotationDetailModel::where('quotation_id',$data->id)->get(),

            'clients' => ClientModel::where(['status'=>'on','id'=>$data->client_id])->first(),

            'shipto' => ShipToModel::where(['status'=>'on','id'=>$data->shipto_id])->first(),

            'airports' => AirportModel::where(['status'=>'on','id'=>$data->airport_id])->first(),

            'airlines' => AirlineModel::where(['status'=>'on','id'=>$data->airline_id])->first(),

            'currencys' => CurrencyModel::where(['status'=>'on','id'=>$data->currency_id])->first(),

            'clearance' => ClearanceModel::select('clearance.*','v.name')->leftJoin('vendors as v','clearance.vendor','=','v.id')->where(['clearance.status'=>'on','clearance.id'=>$data->clearance_id])->first(),

            'units' => UnitCountModel::whereIn('id', [1,2,4])->get(),

        ]);

    }



    public function edit($id)

    {

        // echo "$this->prefix.pages.$this->folder.index";

        // die;

        $data = QuotationModel::find($id);

// echo"<pre>";

//         print_r($data->total_cnf);

//         die;
    // $shipto =  ShipToModel::where(['status'=>'on','client'=>$data->client_id])->first();
    // echo "<pre>"; print_r($shipto); die;

        return view("$this->prefix.pages.$this->folder.index",[

            'css'=> [

                'public/back-end/css/table-responsive.css',

                'public/back-end/css/select2.min.css'

            ],

            'js' => [

                ['type'=>"text/javascript",'src'=>"public/back-end/js/jquery.min.js",'class'=>"view-script"],

                ["src"=>'public/back-end/js/select2.min.js'],

                ["src"=>'public/back-end/js/sweetalert2.all.min.js'],

                ['src'=>"public/back-end/js/table-dragger.min.js"],

                ["type"=>"text/javascript","src"=>"public/back-end/build/$this->folder.js"],

            ],

            'prefix' => $this->prefix,

            'folder' => $this->folder,

            'page' => 'edit',

            'segment' => $this->segment,

            'row' => $data,

            'details' => QuotationDetailModel::where('quotation_id',$data->id)->get(),

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



    public function update(Request $request, $id)

    {

        // echo"<pre>";

        // print_r($request->all());

         //die;

        $quotation = QuotationModel::find($id);

        $quotation->code = $request->code;

        $quotation->user_id = $request->user;

        $quotation->client_id = $request->client;

        $quotation->shipto_id = $request->shipto;

        $quotation->airport_id = $request->airport;

        $quotation->airline_id = $request->airline;

        $quotation->clearance_id = $request->select_clearance;

        $quotation->select_pallet = $request->select_pallet;

        $quotation->select_chamber = $request->select_chamber;

        $quotation->currency_id = $request->currency;

        $quotation->updated = date('Y-m-d H:i:s');

        $quotation->ex_rate = $request->ex_rate;

        $quotation->markup_rate = $request->markup_rate;

        $quotation->rebate = $request->rebate;

        $quotation->ship_date = $request->ship_date;

        $quotation->clearance = $request->clearance;

        $quotation->chamber = $request->chamber;

        $quotation->clearance_price = $request->clearance_price;

        $quotation->transport = $request->transport;

        $quotation->transport_price = $request->transport_price;

        $quotation->total_box = $request->total_box;

        $quotation->total_nw = $request->total_nw;

        $quotation->total_gw = $request->total_gw;

        $quotation->total_cbm = $request->total_cbm;

        $quotation->palletized = $request->palletized;

        $quotation->palletized_price = $request->palletized_price;

        $quotation->total_freight = $request->total_freight;

        $quotation->total_fob = $request->total_fob;

        $quotation->total_pro_before_rebate = $request->total_pro_before_rebate;

        $quotation->total_pro_after_rebate = $request->total_pro_after_rebate;

        $quotation->total_pro_percent = $request->total_pro_percent;

        $quotation->total_cnf = $request->total_cnf;

        $quotation->markup_rateCal = $request->markup_rateCal;

        $quotation->freights = $request->freights;

       





         $total_fob= floatval(preg_replace("/[^-0-9\.]/","",$request->total_fob));

         $total_freight= floatval(preg_replace("/[^-0-9\.]/","",$request->total_freight));

     

        $quotation->total_cnf = floatval($total_fob)+floatval($total_freight);



        $quotation->save();

        foreach($request->itf as $key => $val){

            $detail = QuotationDetailModel::find(@$request->detail_id[$key]);

            if(@$detail->id){

                $detail->itf_id = $request->itf[$key];

                $detail->qty = $request->quantity[$key];

                $detail->unitcount_id = $request->unitcount[$key];

                $detail->ean_id = $request->ean_id[$key];

                $detail->ean_qty = $request->ean_qty[$key];

                $detail->net_weight = $request->net_weight[$key];

                $detail->new_weight = $request->new_weight[$key];

                $detail->maxcbm = $request->maxcbm[$key];

                $detail->maxpallet = $request->maxpallet[$key];

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

              /*  $detail->total_itf_cost = $request->total_itf_cost[$key];*/

                $detail->total_itf_cost = $request->itotal_cost[$key];

                $detail->unit_price = $request->unit_price[$key];

                $detail->profit = $request->profit[$key];

                $detail->profit2 = $request->profit2[$key];

                $detail->fob = $request->itf_fob[$key];

                $detail->net_weightNew = $request->net_weightNew[$key];

                $detail->itf_GW = $request->itf_GW[$key];

                $detail->ean_ppITF = $request->ean_ppITF[$key];

                $detail->itfQty = $request->itfQty[$key];

                $detail->net_weight2 = $request->net_weight2[$key];

                $detail->hpl_avg_weight = $request->hpl_avg_weight[$key];

                $detail->box_pallet = $request->box_pallet[$key];

                $detail->box_weight = $request->box_weight[$key];

                $detail->fx_price = $request->fixPrice[$key];

                $detail->itf_fx_price = $request->itf_fx_price[$key];

                $detail->updated = date('Y-m-d H:i:s');



                 $detail->itf_cal_selling = $request->itf_cal_selling[$key];

                 $detail->itotal_cost = $request->itotal_cost[$key];

                 $detail->rean_cost = $request->rean_cost[$key];

                 $detail->rclearance = $request->rclearance[$key];

                 $detail->rchamber = $request->rchamber[$key];

                 $detail->rtruck = $request->rtruck[$key];

                 $detail->rpallets = $request->rpallets[$key];

                 $detail->rifreight = $request->rifreight[$key];



                $detail->save();

            }else{

                $new = new QuotationDetailModel;

                $new->quotation_id = $quotation->id;

                $new->itf_id = $request->itf[$key];

                $new->qty = $request->quantity[$key];

                $new->unitcount_id = $request->unitcount[$key];

                $new->ean_id = $request->ean_id[$key];

                $new->ean_qty = $request->ean_qty[$key];

                $new->net_weight = $request->net_weight[$key];

                $new->new_weight = $request->new_weight[$key];

                $new->maxcbm = $request->maxcbm[$key];

                $new->maxpallet = $request->maxpallet[$key];

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

                /*$new->itf_freight_rate = $request->itf_freight_rate[$key];*/

                $new->total_itf_cost = $request->itotal_cost[$key];

                $new->unit_price = $request->unit_price[$key];

                $new->profit = $request->profit[$key];

                $new->profit2 = $request->profit2[$key];

                $new->fob = $request->itf_fob[$key];

                $new->net_weightNew = $request->net_weightNew[$key];

                $new->itf_GW = $request->itf_GW[$key];

                $new->ean_ppITF = $request->ean_ppITF[$key];

                $new->itfQty = $request->itfQty[$key];

                $new->net_weight2 = $request->net_weight2[$key];

                $new->hpl_avg_weight = $request->hpl_avg_weight[$key];

                $new->box_pallet = $request->box_pallet[$key];

                $new->box_weight = $request->box_weight[$key];

                $new->fx_price = $request->fixPrice[$key];

                $new->itf_fx_price = $request->itf_fx_price[$key];



                $new->itotal_cost = $request->itotal_cost[$key];

                 $new->rean_cost = $request->rean_cost[$key];

                 $new->rclearance = $request->rclearance[$key];

                 $new->rchamber = $request->rchamber[$key];

                 $new->rtruck = $request->rtruck[$key];

                 $new->rpallets = $request->rpallets[$key];

                 $new->rifreight = $request->rifreight[$key];





                $new->created = date('Y-m-d H:i:s');

                $new->updated = date('Y-m-d H:i:s');

                $new->save();

            }   

        }

        if($quotation->id)

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
                //echo "<pre>"; print_r($data);

                $air = AirportModel::where(['status'=>'on','id'=>$data->airport])->first();

                $text['airport'] = '<option value="'.$air->id.'">'.$air->country.' - '.$air->city.' ['.$air->airport_code.']'.'</option>';

    

                $airlines = FreightModel::select('freight.*','line.id as airline_id','line.name','line.airline_code')->leftJoin('airline as line','freight.airline','=','line.id')->where(['freight.status'=>'on','destination'=>$air->id])->groupBy('airline')->get();

                $text['airline'] = '<option value="">Select airline</option>';

                foreach($airlines as $line)

                {

                    $text['airline'].='<option value="'.$line->airline_id.'">'.$line->name.' ['.$line->airline_code.']'.'</option>';

                }
                $text['markup_rate'] = $data->markup_rate;
                $text['rebate'] = $data->rebate;
                $text['commission_type'] = $data->commission;
                $text['commission_value'] = $data->commission_value;

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

    public function getCurrencyValue(Request $request)
    {
        $data = CurrencyModel::where('id',$request->currency_id)->first();
        if(!isset($data))
        {
            return "na";
        }
        else
        {
            $text['default_value'] = $data->default_value;
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

                $qty = floatval($num_box)*floatval($data->qty);



                    $cal = floatval($first->balance)-floatval($qty);

                

                     $cost = floatval($first->cost_ean);

                     $avg=AverageModel::where(['ean_id'=>$data->item])->orderby("id",'desc');

                     if($avg->count()>0)

                     {

                        $cost=$avg->first()->average;

                     }



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





    public function approve(Request $request)

    {



        $id = $request->id;

        $data = QuotationModel::find($id);

        $data_detail = QuotationDetailModel::where('quotation_id',$id)->get();

        $clear = ClearanceModel::where('id',$data->clearance_id)->first();

        $freight = FreightModel::where(['vendor'=>$clear->vendor,'destination'=>$data->airport_id,'airline'=>$data->airline_id])->first();

        $code = QuotationModel::getOCode();

		//echo $code; die;





        $fob = 0;

        $total_all_cost = 0;

        $nego_rate = 0;

        if($data->total_gw >= 45 && $data->total_gw < 100 || $data->total_gw >= 45 && $freight->rate_100 == 0){

            $nego_rate = $freight->nego_rate_45;

        }elseif($data->total_gw >= 100 && $data->total_gw < 250 || $data->total_gw >= 100 && $freight->rate_250 == 0){

            $nego_rate = $freight->nego_rate_100;

        }elseif($data->total_gw >= 250 && $data->total_gw < 500 || $data->total_gw >= 250 && $freight->rate_500 == 0){

            $nego_rate = $freight->nego_rate_250;

        }elseif($data->total_gw >= 500 && $data->total_gw < 1000 || $data->total_gw >= 500 && $freight->rate_1000 == 0){

            $nego_rate = $freight->nego_rate_500;

        }elseif($data->total_gw >= 1000 && $data->total_gw < 2000 || $data->total_gw >= 1000 && $freight->rate_2000 == 0){

            $nego_rate = $freight->nego_rate_1000;

        }elseif($data->total_gw >= 2000 && $freight->rate_2000 != 0){

            $nego_rate = $freight->nego_rate_2000;

        }



        if(@$data)

        {

            $order = new OrderModel;





             $shipto = ShipToModel::where('id',$data->shipto_id)->first();

             $tt_ref = OrderModel::getTTRef($shipto->id,$shipto->code); 



            $order->tt_ref =$tt_ref;



            $order->code = $code;

            

            $order->quotation_id = $id;

            $order->user_id = Auth::user()->id;

            $order->client_id = $data->client_id;

            $order->shipto_id = $data->shipto_id;

            $order->airport_id = $data->airport_id;

            $order->airline_id = $data->airline_id;

            $order->clearance_id = $data->clearance_id;

            $order->select_pallet = $data->select_pallet;

            $order->select_chamber = $data->select_chamber;

            $order->currency_id = $data->currency_id;

            $order->created = date('Y-m-d H:i:s');

            $order->updated = date('Y-m-d H:i:s');

            $order->status = 'pending';

            $order->ex_rate = $data->ex_rate;

            $order->markup_rate = $data->markup_rate;

            $order->rebate = $data->rebate;

            $order->ship_date = $data->ship_date;

            $order->clearance = $data->clearance;

            $order->chamber = $data->chamber;

            $order->clearance_price = $data->clearance_price;

            $order->transport = $data->transport;

            $order->transport_price = $data->transport_price;

            $order->total_box = $data->total_box;

            $order->total_nw = $data->total_nw;

            $order->total_gw = $data->total_gw;

            $order->total_cbm = $data->total_cbm;

            $order->palletized = $data->palletized;

            $order->palletized_price = $data->palletized_price;

            $order->total_freight = $data->total_freight;

           

            $order->markup_rateCal = $data->markup_rateCal;

            $order->freights = $data->freights;



            $order->total_fob = $data->total_fob;

            $order->total_pro_before_rebate = $data->total_pro_before_rebate;

            $order->total_pro_after_rebate = $data->total_pro_after_rebate;

            $order->total_pro_percent = $data->total_pro_percent;

            

             $total_fob= floatval(preg_replace("/[^-0-9\.]/","",$data->total_fob));

            $total_freight= floatval(preg_replace("/[^-0-9\.]/","",$data->total_freight));

     

            $order->total_cnf = floatval($total_fob)+floatval($total_freight);



            $data->status = 'approve';



            // $order->tt_ref = QuotationModel::getTTRef($data->client_id);

            $order->save();

            foreach($data_detail as $key => $val){

                //$total_itf_cost = round($val->itf_clearance_price+$val->itf_transport_price+$val->itf_cost_price+(floatval($nego_rate)*$val->gw_weight),4);

               //$profit = round((($val->unit_price*$data->ex_rate*$val->qty)-$total_itf_cost)/$total_itf_cost*100,2);

                $detail = new OrderDetailModel;

                $detail->order_id = $order->id;

                $detail->itf_id = $val->itf_id;

                $detail->qty = $val->qty;

                $detail->unitcount_id = $val->unitcount_id;

                $detail->ean_id = $val->ean_id;

                $detail->ean_qty = $val->ean_qty;

                $detail->net_weight = $val->net_weight;

                $detail->new_weight = $val->new_weight;

                $detail->maxcbm = $val->maxcbm;

                $detail->maxpallet = $val->maxpallet;

                $detail->number_box = $val->number_box;

                $detail->nw = $val->nw;

                $detail->gw_weight = $val->gw_weight;

                $detail->cbm = $val->cbm;

                $detail->pallet = $val->pallet;

                $detail->price_allocation = $val->price_allocation;

                $detail->price_pallet_unit = $val->price_pallet_unit;

                $detail->itf_pallet_price = $val->itf_pallet_price;

                $detail->itf_clearance_price = $val->itf_clearance_price;

                $detail->itf_transport_price = $val->itf_transport_price;

                $detail->itf_cost_price = $val->itf_cost_price;

                $detail->itf_freight_rate =$val->itf_freight_rate;// round(floatval($nego_rate)*$val->gw_weight,4);

                $detail->total_itf_cost = $val->total_itf_cost;

                $detail->unit_price = $val->unit_price;

                $detail->profit = $val->profit;

                $detail->fob = $val->fob;//round($val->unit_price*$data->ex_rate*$val->qty,4)-round(floatval($nego_rate)*$val->gw_weight,4);

                $detail->created = date('Y-m-d H:i:s');

                $detail->updated = date('Y-m-d H:i:s');



                $detail->profit2 = $val->profit2;

                $detail->fob = $val->fob;

                $detail->net_weightNew = $val->net_weightNew;

                $detail->itf_GW = $val->itf_GW;

                $detail->ean_ppITF = $val->ean_ppITF;

                $detail->itfQty = $val->itfQty;

                $detail->net_weight2 = $val->net_weight2;

                $detail->hpl_avg_weight = $val->hpl_avg_weight;

                $detail->box_pallet = $val->box_pallet;

                $detail->box_weight = $val->box_weight;

                $detail->itf_cal_selling = $val->itf_cal_selling;

                $detail->fx_price = $val->fx_price;

                $detail->itf_fx_price = $val->itf_fx_price;



                 $detail->itotal_cost = $val->itotal_cost;

                 $detail->rean_cost = $val->rean_cost;

                 $detail->rclearance = $val->rclearance;

                 $detail->rchamber = $val->rchamber;

                 $detail->rtruck = $val->rtruck;

                 $detail->rpallets = $val->rpallets;

                 $detail->rifreight = $val->rifreight;







                $detail->save();

                $fob += $val->unit_price*$data->ex_rate*$val->qty;

                $total_all_cost += $detail->total_itf_cost;

                

                $oper = new OperationModel;

                $oper->order_id = $order->id;

                $oper->order_detail_id = $detail->id;

                $oper->created = date('Y-m-d H:i:s');

                $oper->save();

            }

        

        }

        if($data->save()){

            return response()->json(true);

        }else{

            return response()->json(true);

        }

    }



    public function cancel(Request $request)

    {

        $id = $request->id;

        $data = QuotationModel::find($id);



        

        if(@$data)

        {

            $data->status = 'cancel';

        }

        if($data->save()){

            return response()->json(true);

        }else{

            return response()->json(false);

        }

    }



    public function destroyITF(Request $request)

    {

        $data = QuotationDetailModel::destroy($request->id);

        if($data){

            return "true";

        }else{

            return "false";

        }

    }



    /*sourabh*/

    public function copy($id)

    {

        $data = QuotationModel::find($id);

        return view("$this->prefix.pages.$this->folder.index",[

            'css'=> [

                'public/back-end/css/table-responsive.css',

                'public/back-end/css/select2.min.css'

            ],

            'js' => [

                ['type'=>"text/javascript",'src'=>"public/back-end/js/jquery.min.js",'class'=>"view-script"],

                ["src"=>'public/back-end/js/select2.min.js'],

                ["src"=>'public/back-end/js/sweetalert2.all.min.js'],

                ['src'=>"public/back-end/js/table-dragger.min.js"],

                ["type"=>"text/javascript","src"=>"public/back-end/build/$this->folder.js"],

            ],

            'prefix' => $this->prefix,

            'folder' => $this->folder,

            'page' => 'copy',

            'segment' => $this->segment,

            'row' => $data,

            'details' => QuotationDetailModel::where('quotation_id',$data->id)->get(),

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

    /*sourabh*/

}

