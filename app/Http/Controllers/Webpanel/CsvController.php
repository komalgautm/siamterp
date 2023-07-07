<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use PDF;
use Excel;
use App\Exports\POExport;
use App\Exports\StatementsExp;
use App\ItemModel;
use App\POModel;
use App\QuotationModel;
use App\OrderModel;
use App\InvoiceModel;
use App\ClientModel;
use App\QuotationDetailModel;
use App\CurrencyModel;
use App\Exports\PoCsvExport;

class CsvController extends Controller
{
    protected $prefix = 'back-end';

	public function po(Response $response, $id)
	{
        // from Webpanel/PO@show
        $query =  DB::table('imports as im')->leftJoin('items as it' , 'item', '=', 'it.id')
        ->leftJoin('unit_count as unit' , 'unit_count', '=', 'unit.id')
        ->select('im.*','it.name_th as name_th_item','it.name_en as name_en_item','unit.name_th as name_th_unit','unit.name_en as name_en_unit')
->where('po',$id)->orderBy('it.id');
	$headings = array_keys(get_object_vars($query->first()));


        $query =  DB::table('imports as im')->leftJoin('items as it' , 'item', '=', 'it.id')
        ->leftJoin('unit_count as unit' , 'unit_count', '=', 'unit.id')
        ->select('im.*','it.name_th as name_th_item','it.name_en as name_en_item','unit.name_th as name_th_unit','unit.name_en as name_en_unit')
->where('po',$id)->orderBy('it.id');


	$rows = $query->get();
        $data =  view("$this->prefix.pages.csv.generic",[
	'headings' => $headings,
	'rows' => $rows
	]);
	$filename="po".$id.".csv";
	return response($data)->withHeaders([
		'Content-Type' =>'text/csv', 
		'Content-Disposition' => "attachment; filename: $filename" 
	]); 
    }

    public function quotation(Response $response, $id) {

	 $quotation = QuotationModel::find($id);
         $currency = CurrencyModel::where(['status'=>'on','id'=>$quotation->currency_id])->first();

	$query = QuotationDetailModel::where('quotation_id',$id);
	$headings = array_keys(get_object_vars($query->first()));

	$query = QuotationDetailModel::where('quotation_id',$id);

	$rows = $query->get();
        $data =  view("$this->prefix.pages.csv.quotation",[
	'headings' => $headings,
	'rows' => $rows,
	'currency' => $currency->currency
	]);
	$filename="quotation".$id.".csv";
	return response($data)->withHeaders([
		'Content-Type' =>'text/csv', 
		'Content-Disposition' => "attachment; filename: $filename" 
	]); 

    }

}
