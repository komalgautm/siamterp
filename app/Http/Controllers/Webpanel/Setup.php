<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ProductModel;
use App\SetupModel;
use App\UnitCountModel;
use App\ItemModel;

class Setup extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'setup';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = ProductModel::orderBy('created','desc')
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
            // 'unit' => UnitCountModel::select('id','name_th as unit_th','name_en as unit_en')->where('status','on')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $po = new ProductModel;
        $po->code = $request->code;
        $po->name = $request->product_name;
        $po->created = date('Y-m-d H:i:s');
        $po->updated = date('Y-m-d H:i:s');
        $po->status = 'on';
        $po->user = $request->user;
        $po->total_weight = $request->total_weight;
        $po->total_qty = $request->total_qty;
        if($po->save())
        {
            for($i=0; $i<count($request->item); $i++)
            {
                $setup = new SetupModel;
                $setup->product = $po->id;
                $setup->type = $request->type[$i];
                $setup->item = $request->item[$i];
                $setup->qty = $request->quantity[$i];
                $setup->avg_weight = $request->avg_weight[$i];
                $setup->unit = $request->unitcount[$i];
                $setup->weight = $request->weight[$i];
                $setup->wrap_weight = $request->wrap_weight[$i];
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
        
        $row = ProductModel::find($id);
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
            'setup' => SetupModel::where('product',$row->id)->get(),
            'item' => ItemModel::select('id','name_th','name_en')->where(['type'=>'item','status'=>'on'])->get(),
            'packaging' => ItemModel::select('id','name_th','name_en')->where(['type'=>'packaging','status'=>'on'])->get(),
            'unit' => UnitCountModel::select('id','name_th as unit_th','name_en as unit_en')->where('status','on')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $po = ProductModel::find($id);
        $po->name = $request->product_name;
        $po->total_weight = $request->total_weight;
        $po->total_qty = $request->total_qty;
        $po->updated = date('Y-m-d H:i:s');
        $po->save();
        foreach($request->type as $key => $val){
            $set = SetupModel::find(@$request->set_id[$key]);
            if(@$set->id){
                $set->type = $request->type[$key];
                $set->item = $request->item[$key];
                $set->qty = $request->quantity[$key];
                $set->avg_weight = $request->avg_weight[$key];
                $set->unit = $request->unitcount[$key];
                $set->weight = $request->weight[$key];
                $set->wrap_weight = $request->wrap_weight[$key];
                $set->updated = date('Y-m-d H:i:s');
                $set->save();
            }else{
                $setup = new SetupModel;
                $setup->product = $po->id;
                $setup->type = $request->type[$key];
                $setup->item = $request->item[$key];
                $setup->qty = $request->quantity[$key];
                $setup->avg_weight = $request->avg_weight[$key];
                $setup->unit = $request->unitcount[$key];
                $setup->weight = $request->weight[$key];
                $setup->wrap_weight = $request->wrap_weight[$key];
                $setup->created = date('Y-m-d H:i:s');
                $setup->updated = date('Y-m-d H:i:s');
                $setup->save();
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
        $datas = ProductModel::find($id);
        $setup = SetupModel::where('product',$id)->get();
        if(@$datas)
        {
            foreach($setup as $set)
            {
                SetupModel::destroy($set->id);
            }
            foreach($datas as $data)
            {
                $query = ProductModel::destroy($data->id);
            }
        }
        if($query){
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }

    public function getItem()
    {
        $datas = DB::table('items as itm')->where(['type'=>'item','status'=>'on'])->get();
        // $datas = DB::table('sorting as sort')->leftJoin('items as itm','sort.item', '=', 'itm.id')->groupBy('itm.id','sort.unit')->where('sort.type','item')->get();
        $text = '<option value="">Select Produce</option>';
        foreach($datas as $data)
        {
            $text.='<option value="'.$data->id.'">'.$data->name_th." / ".$data->name_en.'</option>';
        }
        return $text;
    }

    public function getUnit(Request $request)
    {
        $id = $request->id;
        $datas = DB::table('unit_count as units')->where('status','on')->get();
        // $chk = ItemModel::find($id);
        // if($chk->type == 'item'){
        //     $datas = DB::table('sorting as sort')->leftJoin('unit_count as units','sort.unit', '=', 'units.id')->groupBy('sort.item','units.id')->where('sort.item',$id)->get();
        // }else{
        //     $datas = DB::table('receiving as re')->leftJoin('unit_count as units','re.unit', '=', 'units.id')->groupBy('re.item','units.id')->where('re.item',$id)->get();
        // }
        $text='';
        $isItem=DB::table("items")->where("type","item")->where("id",$id)->count();
        if($isItem==0)
        {
           $text = '<option value="" disable>Select Unit</option>'; 
        }
        
        foreach($datas as $data)
        {

            

           
            if($isItem>0)
            {
               if($data->id=='2')
               {
                 $text="2";
                break;
               } 
            }
            else
            {
                 $text.='<option value="'.$data->id.'">'.$data->name_th." / ".$data->name_en.'</option>';
            }
            
        }
        return $text;
    }

    public function getBox()
    {
        $datas = DB::table('items as itm')->where(['type'=>'boxes','status'=>'on'])->get();
        // $datas = DB::table('receiving as re')->leftJoin('items as itm','re.item', '=', 'itm.id')->groupBy('itm.id','re.unit')->where('re.type','boxes')->get();
        $text = '<option value="">Select Produce</option>';
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
        $text = '<option value="">Select Produce</option>';
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
        }else{
            $text['weight'] = "";
            $text['wrap_weight'] = "";
        }
        return $text;
    }

    public function destroySetup(Request $request)
    {
        $data = SetupModel::destroy($request->id);
        if($data){
            return "true";
        }else{
            return "false";
        }
    }

    public function status(Request $request)
    {
        $get = ProductModel::find($request->id);
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
