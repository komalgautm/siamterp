<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ProductModel;
use App\ItemModel;
use App\ITFModel;
use App\ITFdetailModel;
use App\UnitCountModel;

class ITF extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'itf';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = ITFModel::orderBy('created','desc')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('code','like',"%{$keyword}%")
                ->orWhere('name','like',"%{$keyword}%");
            });

        if($view=='all'){
            $rows = $query->get();
        }else{
            $view = ($request->view)? $view : 10;
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
            'segment' => "$this->segment",
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
        ]);
    }

    public function store(Request $request)
    {
        $po = new ITFModel;
        $po->code = $request->code;
        $po->name = $request->product_name;
        $po->created = date('Y-m-d H:i:s');
        $po->updated = date('Y-m-d H:i:s');
        $po->status = 'on';
        $po->user = $request->user;
        $po->all_weight = $request->all_weight;
        $po->adjust_weight = $request->adjust_weight;
        $po->new_weight = $request->new_weight;
        $po->net_weight = $request->net_weight;
        $po->maxcbm = $request->maxcbm;
        $po->maxminload = $request->maxminload;
        $po->maxbox_pallet = $request->maxbox_pallet;
        $po->ean_ppITF = $request->ean_ppITF[0];

        // echo"<pre>";
        // print_r($request->eanQty);
        // die;
        if($po->save())
        {
            for($i=0; $i<count($request->item); $i++)
            {
                $setup = new ITFdetailModel;
                $setup->itf = $po->id;
                $setup->type = $request->type[$i];
                $setup->item = $request->item[$i];
                $setup->qty = $request->quantity[$i];
                $setup->eanQty = $request->eanQty[$i];
                $setup->ean_qty = $request->total_qty[$i];
                $setup->total_weight = $request->total_weight[$i];
                $setup->unit = $request->unitcount[$i];
                $setup->weight = $request->weight[$i];
                $setup->wrap_weight = $request->wrap_weight[$i];
                $setup->cbm = $request->cbm[$i];
                $setup->minload = $request->minload[$i];
                $setup->box_pallet = $request->box_pallet[$i];
                if(strtolower($request->type[$i])=='ean')
                {
                  $setup->ean_ppITF = $request->ean_ppITF[$i];  
                }
               
                
                $setup->created = date('Y-m-d H:i:s');
                $setup->updated = date('Y-m-d H:i:s');
                $setup->save();
            }
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
        $row = ITFModel::find($id);
        $eanQty="SELECT s.qty from setups s where s.type='item' and s.product=itf_detail.item limit 1";
        // DB::table("setups")->where("product",$id)->first()->qty;
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
            'row' => $row,
            'setup' => ITFdetailModel::select('*')->selectSub($eanQty,'eanQty')->where('itf',$row->id)->get(),
            'item' => ProductModel::get(),
            'box' => ItemModel::select('id','name_th','name_en')->where(['type'=>'boxes','status'=>'on'])->get(),
            'packaging' => ItemModel::select('id','name_th','name_en')->where(['type'=>'packaging','status'=>'on'])->get(),
            'unit' => UnitCountModel::select('id','name_th as unit_th','name_en as unit_en')->where('status','on')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $po = ITFModel::find($id);
        $po->name = $request->product_name;
        $po->all_weight = $request->all_weight;
        $po->adjust_weight = $request->adjust_weight;
        $po->new_weight = $request->new_weight;
        $po->net_weight = $request->net_weight;
        $po->maxcbm = $request->maxcbm;
        $po->maxminload = $request->maxminload;
        $po->maxbox_pallet = $request->maxbox_pallet;
        $po->ean_ppITF = $request->ean_ppITF[0];
        $po->updated = date('Y-m-d H:i:s');
        $po->save();
        foreach($request->type as $key => $val){
            $set = ITFdetailModel::find(@$request->set_id[$key]);
            if(@$set->id){
                $set->type = $request->type[$key];
                $set->item = $request->item[$key];
                $set->qty = $request->quantity[$key];
                $set->eanQty = $request->eanQty[$key];
                $set->ean_qty = $request->total_qty[$key];
                $set->eanQty = $request->eanQty[$key];
                $set->total_weight = $request->total_weight[$key];
                $set->unit = $request->unitcount[$key];
                $set->weight = $request->weight[$key];
                $set->wrap_weight = $request->wrap_weight[$key];
                $set->cbm = $request->cbm[$key];
                $set->minload = $request->minload[$key];
                $set->box_pallet = $request->box_pallet[$key];
                $set->updated = date('Y-m-d H:i:s');
                if(strtolower($request->type[$key])=='ean')
                {
                  $set->ean_ppITF = $request->ean_ppITF[$key];  
                }
                $set->save();
            }else{
                $set = new ITFdetailModel;
                $set->itf = $po->id;
                $set->type = $request->type[$key];
                $set->item = $request->item[$key];
                $set->qty = $request->quantity[$key];
                $set->eanQty = $request->eanQty[$key];
                $set->total_weight = $request->total_weight[$key];
                $set->unit = $request->unitcount[$key];
                $set->weight = $request->weight[$key];
                $set->wrap_weight = $request->wrap_weight[$key];
                $set->cbm = $request->cbm[$key];
                $set->minload = $request->minload[$key];
                $set->box_pallet = $request->box_pallet[$key];
                 if(strtolower($request->type[$key])=='ean')
                {
                  $set->ean_ppITF = $request->ean_ppITF[$key];  
                }
                $set->created = date('Y-m-d H:i:s');
                $set->updated = date('Y-m-d H:i:s');
                $set->save();
            }   
        }
        if($po->id)
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function destroy(Request $request)
    {
        $id = explode(',',$request->id);
        $datas = ITFModel::find($id);
        $setup = ITFdetailModel::where('itf',$id)->get();
        if(@$datas)
        {
            foreach($setup as $set)
            {
                ITFdetailModel::destroy($set->id);
            }
            foreach($datas as $data)
            {
                $query = ITFModel::destroy($data->id);
            }
        }
        if($query){
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }

    public function getTotal(Request $request)
    {
        $id = $request->id;
        $data = ProductModel::find($id);
        $total['total_weight'] = $data->total_weight;
        $total['total_qty'] = $data->total_qty;
        $total['eanQty'] = DB::table("setups")->where("product",$id)->first()->qty;
        return $total;
    }

    public function getItem()
    {
        $datas = DB::table('products as itm')->get();
        // $datas = DB::table('sorting as sort')->leftJoin('items as itm','sort.item', '=', 'itm.id')->groupBy('itm.id','sort.unit')->where('sort.type','item')->get();
        $text = '<option value="">Select Item</option>';
        foreach($datas as $data)
        {
            $text.='<option value="'.$data->id.'">'.$data->name.'</option>';
        }
        return $text;
    }

    public function getUnit(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        if($type=='ean'){
            $datas = DB::table('unit_count as units')->where('status','on')->whereIn('id',[1,2])->get(); 
        }
        else
        {
             $datas = DB::table('unit_count as units')->where('status','on')->get();
        }
       
        // $chk = ItemModel::find($id);
        // if($chk->type == 'item'){
        //     $datas = DB::table('sorting as sort')->leftJoin('unit_count as units','sort.unit', '=', 'units.id')->groupBy('sort.item','units.id')->where('sort.item',$id)->get();
        // }else{
        //     $datas = DB::table('receiving as re')->leftJoin('unit_count as units','re.unit', '=', 'units.id')->groupBy('re.item','units.id')->where('re.item',$id)->get();
        // }

        $text = '<option value="">Select Unit</option>';
        foreach($datas as $data)
        {
            $text.='<option value="'.$data->id.'">'.$data->name_th." / ".$data->name_en.'</option>';
        }
        return $text;
    }

    public function getBox()
    {
        $datas = DB::table('items as itm')->where(['type'=>'boxes','status'=>'on'])->get();
        // $datas = DB::table('receiving as re')->leftJoin('items as itm','re.item', '=', 'itm.id')->groupBy('itm.id','re.unit')->where('re.type','boxes')->get();
        $text = '<option value="">Select Item</option>';
        foreach($datas as $data)
        {
            $text.='<option value="'.$data->id.'">'.$data->name_th.'</option>';
        }
        return $text;
    }

    public function getPack()
    {
        $datas = DB::table('items as itm')->where(['type'=>'packaging','status'=>'on'])->get();
        // $datas = DB::table('receiving as re')->leftJoin('items as itm','re.item', '=', 'itm.id')->groupBy('itm.id','re.unit')->where('re.type','packaging')->get();
        $text = '<option value="">Select Item</option>';
        foreach($datas as $data)
        {
            $text.='<option value="'.$data->id.'">'.$data->name_th.'</option>';
        }
        return $text;
    }

    public function getVal(Request $request)
    {
        $id = $request->id; 
        $chk = ItemModel::find($id);
        if($chk->type == 'packaging'){
            $text['weight'] = $chk->weight;
            $text['wrap_weight'] = $chk->wrap_weight;
            $text['cbm'] = 0;
            $text['minload'] = 0;
            $text['box_pallet'] = 0;
        }elseif($chk->type == 'boxes'){
            $text['weight'] = $chk->weight;
            $text['wrap_weight'] = "";
            $text['cbm'] = $chk->cbm;
            $text['minload'] = $chk->minload;
            $text['box_pallet'] = $chk->box_pallet;
        }else{
            $text['weight'] = 0;
            $text['wrap_weight'] = "";
            $text['cbm'] = 0;
            $text['minload'] = 0;
            $text['box_pallet'] = 0;
        }
        return $text;
    }

    public function destroyITF(Request $request)
    {
        $data = ITFdetailModel::destroy($request->id);
        if($data){
            return "true";
        }else{
            return "false";
        }
    }

    public function status(Request $request)
    {
        $get = ITFModel::find($request->id);
        if(@$get->id){
            $status = ($get->status=='off')? 'on' : 'off' ;
            $get->status = $status;
            $get->save();
            if($get->id){
                return response()->json(true);
            }else{
                return response()->json(false);
            }
        }
    }
}
