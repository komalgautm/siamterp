<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\VendorModel;
use App\ProvincesModel;
use App\DistrictModel;
use App\SubdistrictModel;
use Session;

class Vendor extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'vendors';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = VendorModel::orderBy('sort','asc')
            ->when($request->keyword,function($find)use($keyword){
                $find->where('name','like',"%{$keyword}%");
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
                ["type"=>"text/javascript","src"=>"public/back-end/build/vendor.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'index',
            'segment' => "$this->segment",
            'rows' => $rows
        ]);
    }
  
    public function create()
    {
        return view("$this->prefix.pages.$this->folder.index",[
            'css'=> ['public/back-end/css/table-responsive.css','public/back-end/css/validate.css'],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"public/back-end/build/vendor.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'add',
            'segment' => $this->segment,
        ]);
    }

    public function store(Request $request)
    {
        $data = new VendorModel;
        $data->name = $request->name;
        $data->id_card = $request->id_card;
        $data->type = $request->type;
        $data->email = $request->email;
        $data->line_id = $request->line_id;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->subdistrict = $request->subdistrict;
        $data->district = $request->district;
        $data->provinces = $request->provinces;
        $data->postcode = $request->postcode;
        $data->bank_name = $request->bank_name;
        $data->bank_number = $request->bank_number;
        $data->bank_account = $request->bank_account;
        $data->status = 'on';
        $data->sort = 1;
        $data->created = date('Y-m-d H:i:s');
        $data->updated = date('Y-m-d H:i:s');
        if($data->save())
        {
            VendorModel::where('id','!=',$data->id)->increment('sort');
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $row = VendorModel::find($id);
        return view("$this->prefix.pages.$this->folder.index",[
            'css' => ['public/back-end/css/table-responsive.css','public/back-end/css/validate.css'],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"public/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ['src'=>"public/back-end/tinymce/tinymce.min.js"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ["type"=>"text/javascript","src"=>"public/back-end/build/vendor.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'edit',
            'segment' => $this->segment,
            'row' => $row
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = VendorModel::find($id);
        $data->name = $request->name;
        $data->id_card = $request->id_card;
        $data->type = $request->type;
        $data->email = $request->email;
        $data->line_id = $request->line_id;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->subdistrict = $request->subdistrict;
        $data->district = $request->district;
        $data->provinces = $request->provinces;
        $data->postcode = $request->postcode;
        $data->bank_name = $request->bank_name;
        $data->bank_number = $request->bank_number;
        $data->bank_account = $request->bank_account;
        $data->updated = date('Y-m-d H:i:s');
        if($data->save())
        {
            return view("$this->prefix/alert/sweet/success",['url'=>url("/$this->folder")]);
        }else{
            return view("$this->prefix/alert/sweet/error",['url'=>$request->fullUrl()]);
        }
    }

    public function destroy(Request $request)
    {
        $id = explode(',',$request->id);
        $datas = VendorModel::find($id);
        if(@$datas)
        {
            foreach($datas as $data)
            {
                //new sort
                VendorModel::where('sort','>',$data->sort)->decrement('sort', 1);
                $query = VendorModel::destroy($data->id);
            }
        }
        
        if($query){
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }

    public function status(Request $request)
    {
        $get = VendorModel::find($request->id);
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

    public function dragsort(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $get = VendorModel::find($request->id);
        if($from!="" && $to !="")
        {
            if($from > $to){
                VendorModel::whereBetween('sort', [$to, $from])->whereNotIn("id",[$get->id])->increment('sort', 1);
            }else{
                VendorModel::whereBetween('sort', [$from, $to])->whereNotIn("id",[$get->id])->decrement('sort', 1);
            }
            $query = VendorModel::where('id',$get->id)->update(['sort'=>$to]);
            return response()->json($query);
        }
        return response()->json(false);
        
    }

    public function getdistrict(Request $request)
    {
        
        $data = DistrictModel::where('_id',$request->id)->get();
        $text = '';
        if($data){
             foreach($data as $d){
                 $text .= '<option value="'.$d->id.'" >'.$d->name_th.'</option>';
             }
             return $text ;
        }
        return [];
        
    }


    public function getsubdistrict(Request $request)
    {
        $data = SubdistrictModel::where('_id',$request->id)->get();
        $text = '';
        if($data){
            foreach($data as $ds){
                $text .= '<option value="'.$ds->id.'" data-postcode="'.$ds->zipcode.'">'.$ds->name_th.'</option>';
            }
            return $text;
        }
        return [];
    }

    public function getprovinces()
    {
        $pro = ProvincesModel::get();
        $text = '';
        if($pro)
        {
            foreach($pro as $p){
                $text .= '<option value="'.$p->id.'" >'.$p->name_th.'</option>';
            }
            return $text;
        }
        return [];
    }
}
