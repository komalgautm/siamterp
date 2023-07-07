<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use App\ItemModel;

class Hourlyrate extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $folder = 'hourlyrate';

    public function index(Request $request)
    {
        return view("$this->prefix.pages.hourlyrate.index",[
            'css'=> ['public/back-end/css/table-responsive.css','public/back-end/css/validate.css'],
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["type"=>"text/javascript","src"=>"/back-end/jquery-validation-1.19.1/dist/jquery.validate.min.js"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                ["type"=>"text/javascript","src"=>"/back-end/build/item.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'page' => 'add',
            'segment' => $this->segment,
			'v'=>DB::table("settings")->first()->wages
        ]);
    }
	
	function store(Request $request)
	{
		//protected $folder = 'hourlyrate';
		DB::table("settings")->update(['wages'=>$request->wages]);
		return redirect($this->folder);
		
	}

}
