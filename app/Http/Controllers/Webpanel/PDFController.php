<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use Excel;
use App\Exports\POExport;
use App\Exports\StatementsExp;
use App\ItemModel;
use App\POModel;
use App\QuotationModel;
use App\OperationModel;;
use App\OrderModel;
use App\InvoiceModel;
use App\ClientModel;

class PDFController extends Controller
{
    protected $prefix = 'back-end';

    // public function testpdf()
	// {
	// 	$pdf = PDF::loadView("$this->prefix/pages/invoice/invoice");
	// 	return @$pdf->setPaper('A4')->stream();
	// }

	public function po($id)
	{
		$data = POModel::find($id);
		$pdf = PDF::loadView("$this->prefix/pages/po/po",$data);
		return @$pdf->setPaper('A5')->stream();
	}

	public function statementsExp(Request $request)
	{
		//excel
		return Excel::download(new StatementsExp($request->vendor,$request->from,$request->to), 'statement.xlsx');
	}

	public function lrp()
	{
		//excel
		return Excel::download(new POExport, 'LRP.xlsx');
	}

	public function oparetion($id)
	{
		$data = OrderModel::find($id);
		$client = ClientModel::find($data->client_id);
		$data['client'] = $client;
		$pdf = PDF::loadView("$this->prefix/pages/order/oparetion",$data);
		return @$pdf->setPaper('A4')->stream();
	}

	public function customs($id)
	{
		$data = OrderModel::find($id);
		$pdf = PDF::loadView("$this->prefix/pages/order/customs",$data);
		return @$pdf->setPaper('A4')->stream();
	}

	public function quotation($id)
	{
		$data = QuotationModel::find($id);
		$pdf = PDF::loadView("$this->prefix/pages/quotation/quotation",$data);
		return @$pdf->setPaper('A4')->stream();
	}

	public function invoice($id)
	{
		$data = InvoiceModel::find($id);
		$order = OrderModel::find($data->order_id);
		$data['order'] = $order;


		// We need this to get the package information for each
		// line-item, and currently we seem to be able to get it from here
        	$data_detail = OperationModel::select('detail.*','operation.box','operation.packaging','operation.adjust','operation.new_gw','operation.packing_weight','operation.over_nw','operation.of_pallets')
        ->leftJoin('orders_detail as detail','operation.order_detail_id','=','detail.id')->where('operation.order_id',$order->id)->get();


		$orderitem_packages_map = [];
		foreach($data_detail as $pt) // for calculations
		{
		    $orderitem_packages_map[$pt->id] = round($pt->packaging);
		}
		$data['orderitem_packages_map'] = $orderitem_packages_map;

	//throw new \Exception(print_r($orderitem_packages_map, true));
		$pdf = PDF::loadView("$this->prefix/pages/invoice/invoices",$data);
	
		return @$pdf->setPaper('A4')->stream();
	}

	public function invoice_report($id)
	{
		$data = InvoiceModel::find($id);
		$pdf = PDF::loadView("$this->prefix/pages/invoice/invoices_r",$data);
		return @$pdf->setPaper('A4')->stream();
	}

	public function claim($id)
	{
		$invoice_id=DB::table("claims")->where('id',$id)->first()->invoice_id;
		$data = InvoiceModel::find($invoice_id);

		// print_r(json_encode(json_decode($data)));
		// die;

		$data['claim_id']=$id;
		$pdf = PDF::loadView("$this->prefix/pages/invoice/claim_print",$data);
		return @$pdf->setPaper('A4')->stream();
	}
}
