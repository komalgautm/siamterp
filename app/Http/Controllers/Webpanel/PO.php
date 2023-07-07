<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\POModel;
use App\Payment;
use App\VendorModel;
use App\ItemModel;
use App\UnitCountModel;
use App\ImportModel;
use App\UserModel;
use App\ReceiveModel;
use App\SortingModel;
use App\TransectionModel;
use App\PackingModel;
use App\BankModel;
use Session;

class PO extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'po';

    public function index(Request $request)
    {
         $keyword = $request->keyword;
        $view = $request->view;
        $from = date($request->date_from);
        $to = date($request->date_to);

        $livendor = VendorModel::all();
      $rowss =DB::table('pos')
            ->leftJoin('vendors', 'pos.vendor', '=', 'vendors.id')
            ->leftJoin(DB::raw('(SELECT po,SUM(total_price) AS total FROM imports GROUP BY po) as im'),'pos.id','=','im.po')
            ->where('pos.paid',"notpaid")
            ->select('pos.*','vendors.id as vendor_id','vendors.name','im.total')

            ->orderBy('pos.created','desc')->get();


        if($request->report == 1){
            DB::enableQueryLog(); // Enable query log
            $query = DB::table('pos')
            ->leftJoin('vendors', 'pos.vendor', '=', 'vendors.id')
            ->leftJoin(DB::raw('(SELECT po,SUM(total_price) AS total FROM imports GROUP BY po) as im'),'pos.id','=','im.po')
            ->select('pos.*','vendors.id as vendor_id','vendors.name','im.total')
            ->orderBy('pos.created','desc');
            if(!empty($from) && !empty($to)){
                $query = $query->whereBetween('pos.created',[$from.' 00:00:00', $to.' 23:59:59']);
            }else if(!empty($from) && empty($to)){
                $query = $query->whereBetween('pos.created',[$from.' 00:00:00', $from.' 23:59:59']);
            }else if(empty($from) && !empty($to)){
                $query = $query->whereBetween('pos.created',[$to.' 00:00:00', $to.' 23:59:59']);
            }

            if(!empty($request->vendor)){
                $query = $query->where('pos.vendor',$request->vendor);
            }
            if(!empty($request->paid)){
                $query = $query->where('pos.paid',$request->paid);
            }
            if(!empty($request->delivered)){
                $query = $query->where('pos.status',$request->delivered);
            }
            $rows = $query->get();
            //dd(DB::getQueryLog());
			// Comment on 05-04-2022
            /*$view = ($request->view)? $view : 10 ;
            $rows = $query->paginate($view);
            $rows->appends(['view' => $request->view,'keyword' => $keyword,'page' => $request->page]);
			*/
        }else{
            $query = DB::table('pos')
            ->leftJoin('vendors', 'pos.vendor', '=', 'vendors.id')
            ->leftJoin(DB::raw('(SELECT po,SUM(total_price) AS total FROM imports GROUP BY po) as im'),'pos.id','=','im.po')
            ->select('pos.*','vendors.id as vendor_id','vendors.name','im.total')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('pos.code','like',"%{$keyword}%")
                ->orWhere('vendors.name','like',"%{$keyword}%");
            })->orderBy('pos.created','desc');
			 $rows = $query->get();
			 // Comment on 05-04-2022 and add above line
            /*if($view=='all'){
                $rows = $query->get();

            }else{
                $view = ($request->view)? $view : 10 ;
                $rows = $query->paginate($view);
                $rows->appends(['view' => $request->view,'keyword' => $keyword,'page' => $request->page]);
            }*/
        }
        $bank=BankModel::where("status","on")->get();
        //return response()->json($rows);
        return view("$this->prefix.pages.po.index",[
            'css'=> [
                'public/back-end/css/table-responsive.css','public/back-end/css/validate.css',
                //'public/back-end/css/select2.min.css'
                'https://eflyerhomes.com/assets/frontend/css/select2.min.css',
				'//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css'
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                //["src"=>'public/back-end/js/select2.min.js'],
                ["src"=>'https://eflyerhomes.com/assets/frontend/js/select2.full.min.js'],
				['src'=>"https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/po.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => 'po',
            'page' => 'index',
            'segment' => "$this->segment",
            'rows' => $rows,
            'rowss' => $rowss,
            'bank' => $bank,
            'livendor'=> $livendor,
        ]);
    } 


    public function create()
    {
        return view("$this->prefix.pages.po.index",[
            'css'=> [
                'public/back-end/css/table-responsive.css',
                'public/back-end/css/validate.css',
                'public/back-end/css/select2.min.css',
                // 'public/back-end/bootstrap-select-1.13.0-dev/dist/css/bootstrap-select.css',
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                // ['src'=>'public/back-end/js/bootstrap.min.js'],
                // ["src"=>'public/back-end/bootstrap-select-1.13.0-dev/dist/js/bootstrap-select.js'],
                ["src"=>'public/back-end/js/select2.min.js'],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/po.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'add',
            'segment' => $this->segment,
            'vendor' => VendorModel::select('id','name')->where('status','on')->get(),
            'item' => ItemModel::select('id','name_th','name_en')->where(['type'=>'item','status'=>'on'])->get(),
            'box' => ItemModel::select('id','name_th')->where(['type'=>'boxes','status'=>'on'])->get(),
            'pack' => ItemModel::select('id','name_th')->where(['type'=>'packaging','status'=>'on'])->get(),
            'unit' => UnitCountModel::select('id','name_th as unit_th','name_en as unit_en')->where('status','on')->get()
        ]);
    }

    public function store(Request $request)
    {
		//return $request; Die;
        $po = new POModel;
        $po->code = POModel::getCode();
        $po->created = date('Y-m-d H:i:s');
        $po->user = $request->user;
        $po->vendor = $request->vendor;
        $po->status = "pending";
        $po->paid = "notpaid";
        if($po->save())
        {
            for($i=0; $i<count($request->item); $i++)
            {
                $it = new ImportModel;
                $it->po = $po->id;
                $it->type = $request->type[$i];
                $it->item = $request->item[$i];
                $it->barcode = $po->code.$request->barcode[$i];
                $it->quantity = $request->quantity[$i];
                $it->price = $request->price[$i];
                $it->unit_count = $request->unitcount[$i];
                $it->crate = $request->crate[$i];
                $it->vat = $request->vat[$i];
                $it->wht = $request->wht[$i];
                $it->total_price =$request->total[$i];
                $it->confirm = 'no';
                $it->save();
            }

             $sql=POModel::get();
        
 
            $totalq=Payment::where(['vendor'=>$request->vendor])->orderby('id','desc');
            $total=0;
            if($totalq->count()>0)
            {
                $total=$totalq->first()->total;
            }

            $ins['created_at']=date("Y-m-d H:i:s");
            $ins['txn_date']=date("Y-m-d H:i:s");
            $ins['transaction']="invoice";
            $ins['vendor']=$request->vendor;
            $ins['amount']= DB::table('imports')->where('po',$po->id)->sum('total_price');
            $ins['total']=$ins['amount'];
            if($total>0)
            {
                $ins['total']=$total+$ins['amount'];
            }
          
            $ins['status']="paid";
            $ins['txn_mode']="cr";
            $ins['notes']=$po->code;
            $ins['numbers']=$po->code;
            if(Payment::where(['vendor'=>$ins['vendor'],'numbers'=>$ins['numbers'],'transaction'=>$ins['transaction']])->count()==0)
            {
             Payment::insert($ins); 
            }
       


            return view("$this->prefix.alert.sweet.success",['url'=>url("$this->folder")]);
        }else{
            return view("$this->prefix.alert.sweet.error",['url'=>$request->fullUrl()]);
        }
    }

    public function show($id)
    {
        $row = POModel::find($id);
        return view("$this->prefix.pages.po.index",[
            'css'=> [
                'public/back-end/css/table-responsive.css',
                'public/back-end/css/validate.css',
                'public/back-end/css/select2.min.css',
                // 'public/back-end/bootstrap-select-1.13.0-dev/dist/css/bootstrap-select.css',
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                // ['src'=>'/back-end/Inputmask-5.x/dist/jquery.inputmask.min.js'],
                // ["src"=>'/back-end/bootstrap-select-1.13.0-dev/dist/js/bootstrap-select.js'],
                ["src"=>'/back-end/js/select2.min.js'],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/po.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'view',
            'segment' => $this->segment,
            'row' => $row,
            'user' => UserModel::where('id',$row->user)->first(),
            'vendor' => VendorModel::where('id',$row->vendor)->first(),
            'import' => DB::table('imports as im')->leftJoin('items as it' , 'item', '=', 'it.id')
            ->leftJoin('unit_count as unit' , 'unit_count', '=', 'unit.id')
            ->select('im.*','it.name_th as name_th_item','it.name_en as name_en_item','unit.name_th as name_th_unit','unit.name_en as name_en_unit')
            ->where('po',$row->id)->get(),
            'total' => DB::table('imports')->where('po',$row->id)->sum('total_price')
        ]);
    }

    public function changeStatus(Request $request, $id)
    {
        $data = POModel::find($id);
        if($request->status == "pickup"){
            $data->status = $request->status;
            $data->pickup_date = date('Y-m-d H:i:s');
        }elseif($request->status == "delivery"){
            $data->status = $request->status;
            $data->delivery_date = date('Y-m-d H:i:s');
        }elseif($request->status == "receive"){
            $data->status = $request->status;
            $data->receive_date = date('Y-m-d H:i:s');
        }
        if($data->save())
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function payment(Request $request)
    {
        $data = POModel::find($request->id);
        if($request->paid == 'paid')
        {
            $data->paid = $request->paid;
            $data->staff_press = $request->user;
            $data->paid_date = $request->paid_date;
            $data->paid_by = $request->paid_by;
            
            if($request->staff_name){
                $data->staff_name = $request->staff_name;
            }
            $ins['created_at']=date("Y-m-d H:i:s");
            $ins['txn_date']=date("Y-m-d H:i:s");
            $ins['transaction']="payment - ".$request->paid_by;

            if($request->paid_by=='staff')
            {
                $ins['transaction']="payment - ".$request->staff_name;
            }
            $ins['vendor']=$data->vendor;
           
             $ins['amount']= DB::table('imports')->where('po',$data->id)->sum('total_price');
             $ins['status']="paid";
             $ins['notes']=$data->code;
          
            
            $totalq=Payment::where(['vendor'=>$data->vendor])->orderby('id','desc');
            $total=0;
            if($totalq->count()>0)
            {
                $total=$totalq->first()->total;
            }
            if($total>0)
            {
                $ins['total']=$total-$ins['amount'];
            }


             if(Payment::where(['vendor'=>$ins['vendor'],'notes'=>$ins['notes'],'transaction'=>$ins['transaction']])->count()==0)
             {
               Payment::insert($ins); 
             }
             

        }
        if($data->save())
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }   

    public function combined_payment(Request $request)
    {

       $post= $request->all();
        
         if($request->paid == 'paid')
        {
            $ins['created_at']=date("Y-m-d H:i:s");
            $ins['txn_date']=date("Y-m-d H:i:s");
            $ins['transaction']="payment - ".$request->paid_by;
            if($request->paid_by=='staff')
            {
                $ins['transaction']="payment - ".$request->staff_name;
            }

            $ins['vendor']=$request->vendor;
            $ins['notes']=$request->notes;
            $po=[];

           
           
                    
            if(isset($post['pos']))
            foreach($post['pos'] as $id)
            {
                   $data = POModel::find($id);
               
                    $data->paid = $request->paid;
                    $data->staff_press = $request->user;
                    $data->paid_date = $request->paid_date;
                    $data->paid_by = $request->paid_by;
                    if($request->staff_name)
                    {
                        $data->staff_name = $request->staff_name;
                    }
                   $po[]=$data->code;
                
                   $data->save();

            }

             $ins['amount']=$request->total;
             $ins['status']="paid";
             $note=implode(" / ", $po);
             $ins['description']="PO Number List
            $note
             ";
            $totalq=Payment::where(['vendor'=>$request->vendor])->orderby('id','desc');
            $total=0;
            if($totalq->count()>0)
            {
                $total=$totalq->first()->total;
            }
            if($total>0)
            {
                $ins['total']=$total-$ins['amount'];
            }

             if(Payment::where(['vendor'=>$ins['vendor'],'description'=>$ins['description'],'transaction'=>$ins['transaction']])->count()==0)
             {
               Payment::insert($ins); 
             }
             

            
       }
        
      
        
         return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
    }

    public function edit($id)
    {
        $data = POModel::find($id);
        return view("$this->prefix.pages.po.index",[
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
                ["type"=>"text/javascript","src"=>"public/back-end/build/po.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'edit',
            'segment' => $this->segment,
            'row' => $data,
            'vendor' => VendorModel::select('id','name')->where('status','on')->get(),
            'item' => ItemModel::select('id','name_th','name_en')->where(['type'=>'item','status'=>'on'])->get(),
            'boxes' => ItemModel::select('id','name_th','name_en')->where(['type'=>'boxes','status'=>'on'])->get(),
            'pack' => ItemModel::select('id','name_th','name_en')->where(['type'=>'packaging','status'=>'on'])->get(),
            'unit' => UnitCountModel::select('id','name_th as unit_th','name_en as unit_en')->where('status','on')->get(),
            'import' => DB::table('imports as im')->leftJoin('items as it' , 'item', '=', 'it.id')
            ->leftJoin('unit_count as unit' , 'unit_count', '=', 'unit.id')
            ->select('im.*','it.name_th as name_th_item','it.name_en as name_en_item','unit.name_th as name_th_unit','unit.name_en as name_en_unit')
            ->where('po',$data->id)->get()
        ]);
    }

    public function update(Request $request, $id)
    {
        //PO
        $data = POModel::find($id);
        $data->vendor = $request->vendor;
        $data->save();
        //import
 //echo"<pre>";
         //print_r($request->all());
 //die;
 
        foreach($request->barcode as $key => $val){
            $imp = ImportModel::find(@$request->imp_id[$key]);
            if(@$imp->id){
                $imp->type = $request->type[$key];
                $imp->item = $request->item[$key];
                
                $imp->barcode = $data->code.$request->barcode[$key];
                $imp->quantity = $request->quantity[$key];
                $imp->unit_count = $request->unitcount[$key];
                $imp->price = $request->price[$key];
                $imp->crate = $request->crate[$key];
               // $imp->total_price = $request->total[$key];
                $imp->total_price =$request->price[$key]*$request->quantity[$key];
                $imp->vat = $request->vat[$key];
                $imp->wht = $request->wht[$key];
                $imp->save();
            }else{
                $val = new ImportModel;
                $val->po = $data->id;
                $val->type = $request->type[$key];
                $val->item = $request->item[$key];
                $val->barcode = $data->code.$request->barcode[$key];
                $val->quantity = $request->quantity[$key];
                $val->unit_count = $request->unitcount[$key];
                $val->price = $request->price[$key];
                $val->crate = $request->crate[$key];
                $val->total_price = $request->price[$key]*$request->quantity[$key];
                $val->confirm = 'no';
                // echo"<pre>";
                // print_r($request->all());
                // die;
                $val->vat = $request->vat[$key];
                $val->wht = $request->wht[$key];
                $val->save();
            }
            
        }
        if($data->id)
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/po")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function destroy($id)
    {
        //
    }

    public function destroyimport(Request $request)
    {
        $data = ImportModel::destroy($request->id);
        if($data){
            return "true";
        }else{
            return "false";
        }
    }

    public function getBarcode(Request $request)
    {
        // $data = ItemModel::where('id',$request->id)->first();
        // return $data->barcode;
		
        //$num = substr("$request->code",-3); //001 // Comment By Malti
        $num = substr("$request->code",-2); //001
        $row = intval($num)+1;
        // $code = POModel::getCode();
        //$gen = substr("000$row",-3);  // Comment By Malti
        $gen = substr("000$row",-2);
        // $nextId = $code.$gen;
        $nextId = $gen;
        return $nextId;
    }
    
    public function getItem()
    {
        $datas = ItemModel::where(['type'=>'item','status'=>'on'])->get();
        $text = '<option value="">Select Produce</option>';
        foreach($datas as $data)
        {
            $text.='<option value="'.$data->id.'">'.$data->name_th." / ".$data->name_en.'</option>';
        }
        return $text;
    }

    public function getUnit()
    {
        $datas = UnitCountModel::where('status','on')->get();
        $text = '<option value="">Select Unit</option>';
        foreach($datas as $data)
        {
            $text.='<option value="'.$data->id.'">'.$data->name_th." / ".$data->name_en.'</option>';
        }
        return $text;
    }

    public function getBox()
    {
        $datas = ItemModel::where(['type'=>'boxes','status'=>'on'])->get();
        $text = '<option value="">Select Produce</option>';
        foreach($datas as $data)
        {
            $text.='<option value="'.$data->id.'">'.$data->name_th.'</option>';
        }
        return $text;
    }

    public function getPack()
    {
        $datas = ItemModel::where(['type'=>'packaging','status'=>'on'])->get();
        $text = '<option value="">Select Produce</option>';
        foreach($datas as $data)
        {
            $text.='<option value="'.$data->id.'">'.$data->name_th.'</option>';
        }
        return $text;
    }

    public function cancel(Request $request)
    {
        $id = $request->id;
        $data = POModel::find($id);
        $imports = ImportModel::where('po',$id)->get();
        foreach($imports as $import)
        {
            $import->confirm = 'no';
            $import->confirm_by = null;
            $import->save();
            $receive = ReceiveModel::where('code',$import->barcode)->first();
            $sorting = SortingModel::where(['code'=>$import->barcode,'type'=>'item'])->first();
            $trans = TransectionModel::where('code',$import->barcode)->get();
            foreach($trans as $tran)
            {
                TransectionModel::destroy($tran->id);
            }
            if($receive){
                $receive->delete();
            }
            if($sorting){
                $sorting->delete();
            }
        }
        if(@$data)
        {
            $data->paid = 'cancel';
            $data->status = 'cancel';
        }
        if($data->save()){
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }

    public function return(Request $request)
    {
        $id = $request->id;
        $imports = ImportModel::where('po',$id)->get();
        $pack = PackingModel::leftJoin('sorting as sort','packing.sorting_id','=','sort.id')
        ->leftJoin('imports as im','sort.code','=','im.barcode')
        ->leftJoin('pos as po','im.po','=','po.id')
        ->where('po.id',$id)->count();
        if($pack > 0){
            return 'false';
        }else{
            foreach($imports as $import)
            {
                $import->confirm = 'no';
                $import->confirm_by = null;
                $import->save();
                $receive = ReceiveModel::where('code',$import->barcode)->first();
                $sorting = SortingModel::where(['code'=>$import->barcode,'type'=>'item'])->first();
                $trans = TransectionModel::where('code',$import->barcode)->get();
                foreach($trans as $tran)
                {
                    TransectionModel::destroy($tran->id);
                }
                if($receive){
                    $receive->delete();
                }
                if($sorting){
                    $sorting->delete();
                }
            }
            $po = POModel::find($id);
            $po->status = 'delivery';
            $po->return_date = date('Y-m-d H:i:s');
            $po->save();
            return 'true';
        }
    }


    function debit_note(Request $request)
    {
          $data = POModel::find($request->id);
          $data->dn_amount = $request->dn_amount;
          $data->dn_note = $request->dn_note;
          if($data->save())
          {
            $ins['created_at']=date("Y-m-d H:i:s");
            $ins['txn_date']=date("Y-m-d H:i:s");
            $ins['transaction']="Debit Note";
            $ins['vendor']=$data->vendor;

             $numbers=Payment::getCode();
             $ins['numbers']=$numbers;
             $ins['amount']= $request->dn_amount;
             $ins['status']="approved";
             $ins['is_dn']="yes";
             $ins['notes']=$data->code;
             $ins['po_no']=$data->code;
            //  echo"<pre>";
            // print_r($ins);
            // die;
            $totalq=Payment::where(['vendor'=>$data->vendor])->orderby('id','desc');
            $total=0;
            if($totalq->count()>0)
            {
                $total=$totalq->first()->total;
            }
            if($total>0)
            {
                $ins['total']=$total-$ins['amount'];
            }

             if(Payment::where(['vendor'=>$ins['vendor'],'po_no'=>$ins['po_no'],'transaction'=>$ins['transaction']])->count()==0)
             {
               Payment::insert($ins); 
             }
             else
             {
                Payment::where(['vendor'=>$ins['vendor'],'po_no'=>$ins['po_no'],'transaction'=>$ins['transaction']])->update($ins);
             }


            return view("$this->prefix.alert.sweet.success",['url'=>url("$this->folder")]);
          }
          else
           {
            return view("$this->prefix.alert.sweet.error",['url'=>url("$this->folder")]);
           }
    }


     function invoice_detail_update(Request $request)
    {
          $data = POModel::find($request->id);
          $data->invoice_no = $request->invoice_no;
          $data->invoice_date = $request->invoice_date;
          if($data->save())
          {
           
            

            
            // $ins['numbers']=$data->invoice_no;
          
             
             $ins['notes']=$data->invoice_no."/$request->invoice_date";
             

           
            Payment::where(['numbers'=>$data->code,'transaction'=>'invoice'])->update($ins);
             


            return view("$this->prefix.alert.sweet.success",['url'=>"view/".$request->id]);
          }
          else
           {
            return view("$this->prefix.alert.sweet.error",['url'=>"view/".$request->id]);
           }
    }


    public function statement(Request $request)
    {

       //  $sql=POModel::get();
        

       // foreach ($sql as $key => $data)
       //  {   
       //      $total=Payment::where(['vendor'=>$data->vendor])->sum('amount');

       //      $ins['created_at']=date("Y-m-d H:i:s");
       //      $ins['txn_date']=date("Y-m-d H:i:s");
       //      $ins['transaction']="invoice";
       //      $ins['vendor']=$data->vendor;
       //      $ins['amount']= DB::table('imports')->where('po',$data->id)->sum('total_price');
       //      $ins['total']=$ins['amount'];
       //      if($total>0)
       //      {
       //          $ins['total']=$total+$ins['amount'];
       //      }
          
       //      $ins['status']="paid";
       //      $ins['txn_mode']="cr";
       //      $ins['notes']=$data->code;
       //      $ins['numbers']=$data->code;
       //      if(Payment::where(['vendor'=>$ins['vendor'],'numbers'=>$ins['numbers'],'transaction'=>$ins['transaction']])->count()==0)
       //      {
       //       Payment::insert($ins); 
       //      }
       // }
       // die;
        $keyword = $request->keyword;
        $view = $request->view;
        $from = date($request->from_date);
        $to = date($request->to_date);
        $vendor = date($request->vendor);

        $vendors = VendorModel::find($vendor);
    
            DB::enableQueryLog(); // Enable query log
            $query = DB::table('payment_transactions as tx')
            ->leftJoin('vendors', 'tx.vendor', '=', 'vendors.id')
            ->select('tx.*','vendors.id as vendor_id','vendors.name')
            ->orderBy('tx.id','asc');


            if(!empty($from) && !empty($to)){
                $query = $query->whereBetween('tx.txn_date',[$from.' 00:00:00', $to.' 23:59:59']);
            }else if(!empty($from) && empty($to)){
                $query = $query->whereBetween('tx.txn_date',[$from.' 00:00:00', $from.' 23:59:59']);
            }else if(empty($from) && !empty($to)){
                $query = $query->whereBetween('tx.txn_date',[$to.' 00:00:00', $to.' 23:59:59']);
            }

            $query = $query->where('tx.vendor',$request->vendor);
            

           
            $rows = $query->get();
            //dd(DB::getQueryLog());

            $view = ($request->view)? $view : 100 ;
            $rows = $query->paginate($view);
            $rows->appends(['view' => $request->view,'keyword' => $keyword,'page' => $request->page]);

        //return response()->json($rows);
        return view("$this->prefix.pages.po.index",[
            'css'=> [
                'public/back-end/css/table-responsive.css','public/back-end/css/validate.css',
                'public/back-end/css/select2.min.css'
            ],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ["src"=>'public/back-end/js/select2.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/po.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => 'po',
            'page' => 'statement',
            'segment' => "$this->segment",
            'rows' => $rows,
            'from' => $from,
            'to' => $to,
            'vendors' => $vendors,
            
        ]);
    }

}
