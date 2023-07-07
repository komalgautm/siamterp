<?php

namespace App\Exports;


use App\Payment;
use App\VendorModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class StatementsExp implements FromView
{
    private $vendor;
    private $from;
    private $to;

    public function __construct($vendor,$from,$to)

    {
        $this->vendor=$vendor;
        $this->from=$from;
        $this->to=$to;

    }
    public function view(): View
    {

		
        $from = date($this->from);
        $to = date($this->to);
		
        $vendor = date($this->vendor);
		
		
        $vendors = VendorModel::find($this->vendor);
    
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

            $query = $query->where('tx.vendor',$this->vendor);
            
			$query1 = DB::table('payment_transactions')
            ->select('amount','id')
			->where('vendor',$this->vendor)
			->where('created_at','<',$from.' 00:00:00')
            ->orderBy('id','desc')
			->limit(1)->first();
			//print_r($query1);
			if(!empty($query1))
			{
				$data['stating_balance']= $query1;
			}
			else
			{
				$data['stating_balance']= (object)(array('amount'=>0));
			}
			
			//echo "<pre>"; print_r($data['stating_balance']); die;
           
           $data['items'] = $query->get();
		   $data['vendor_name'] = DB::table('vendors')->select('name')->where('id',$this->vendor)->first();
		   $data['from_date'] = $from;
		   $data['to_date'] = $to;
		   //echo "<pre>"; print_r($data['items']); die;

        //$data['items']=Payment::get();
        return view('back-end/pages/po/statementsExp', $data);
    }
}
