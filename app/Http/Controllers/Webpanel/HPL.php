<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PackingModel;
use App\PackingDetailModel;

class HPL extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'hpl';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $view = $request->view;
        $query = PackingDetailModel::select('packing_detail.*','units.name_th as unit_th','units.name_en as unit_en','sort.code','sort.avg_weight','pro.code as ean_code','pro.name','pack.id as pack_id','pack.ean_cost','pack.wastage_percent')
        ->leftJoin('packing as pack','pack.id','=','packing_detail.packing')
        ->leftJoin('products as pro','packing_detail.product','=','pro.id')
        ->leftJoin('sorting as sort','pack.sorting_id','=','sort.id')
       // ->leftJoin('unit_count as units','pack.unit','=','units.id')
          ->leftJoin('unit_count as units','packing_detail.ean_unit','=','units.id')
        ->orderBy('created','desc')
        ->when($request->keyword,function($find)use($keyword){
            $find->where('pro.name','like',"%{$keyword}%")
            ->orWhere('sort.code','like',"%{$keyword}%")
            ->orWhere('pro.code','like',"%{$keyword}%");
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
                ["src"=>'/back-publicend/js/select2.min.js'],
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
        //
    }

    public function store(Request $request)
    {
        //
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
}
