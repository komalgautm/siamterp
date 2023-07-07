<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $controller = 'dashboard';
    protected $folder = 'dashboard';

    public function index(Request $request)
    {
        return view("$this->prefix.pages.$this->folder.index",[
            'js' => [
                ['type'=>"text/javascript",'src'=>"/back-end/js/jquery.min.js",'class'=>"view-script"],
                ["src"=>'/back-end/js/sweetalert2.all.min.js'],
                ['src'=>"/back-end/js/table-dragger.min.js"],
                // ["type"=>"text/javascript","src"=>"/back-end/build/dashboard.js"],
            ],
            'prefix' => $this->prefix,
            'folder' => 'dashboard',
            'page' => 'index',
            'segment' => "$this->segment/dashboard",
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
