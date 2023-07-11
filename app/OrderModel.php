<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $fillable = ['code','user_id','client_id','shipto_id','airport_id','airline_id','clearance_id','cerrency_id','ex_rate','markup_rate','rebate','select_pallet','select_chamber','clearance','chamber','clearance_price','transport','transport_price','total_box','total_nw','total_gw','total_cbm','palletized','palletized_price','total_freight','total_fob','total_pro_before_rebate','total_pro_after_rebate','total_pro_percent','status','awb_no','freight_detail','etd','eta','load_date','load_time','ship_date','po_number','total_package','ship_status','created','updated','total_cnf','freights','markup_rateCal'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;

    public static function getOCode()
    {
        // $maxId = DB::table('pos')->orderBy('code','desc')->first();
        // $maxId = DB::table('pos')->whereNull('code')->orderBy('id','desc')->first();
        $maxBC = DB::table('orders')->select('code')->orderBy('code','desc')->orderBy('created','desc')->limit(1)->first();
        $prefix = 'O-';

        if($maxBC != null){
            $cut = substr("$maxBC->code",2); //2020030001
            $cut2 = substr("$cut",0,-3); //202003
            
            if($cut2 == date("Ym")){
                $chk = substr("$maxBC->code",-3); //0001

                if(intval($chk) >= 1){
                    $chk = intval($chk)+1;
                    $gen = substr("0000$chk",-3);
                }else{
                    $gen = substr("00001",-3);
                }

            }else{
                $gen = substr("00001",-3);
            }
        }else{
            $gen = substr("00001",-3);
        }
		
		//echo$gen;
		//die;
        $nextId = $prefix.date("Ym").$gen;
        return $nextId;
    }

 public static function getCode()
    {
        // $maxId = DB::table('pos')->orderBy('code','desc')->first();
        // $maxId = DB::table('pos')->whereNull('code')->orderBy('id','desc')->first();
        $maxBC = DB::table('invoices')->select('code')->orderBy('code','desc')->orderBy('created','desc')->limit(1)->first();
        $prefix = 'INV-';

        if($maxBC != null){
            $cut = substr("$maxBC->code",4); // 2020030001
            $cut2 = substr("$cut",0,-3); // 202003
            
            if($cut2 == date("Ym")){
                $chk = substr("$maxBC->code",-3); //0001

                if(intval($chk) >= 1){
                    $chk = intval($chk)+1;
                    $gen = substr("000$chk",-3);
                }else{
                    $gen = substr("0001",-3);
                }

            }else{
                $gen = substr("0001",-3);
            }
        }else{
            $gen = substr("0001",-3);
        }
        $nextId = $prefix.date("Ym").$gen;
        return $nextId;
    }

    public static function getTTRef($client,$prefix)
	{
        $maxBC = DB::table('orders')->select('tt_ref','created')->where('client_id',$client)->orderBy('tt_ref','desc')->orderBy('created','desc')->limit(1)->first();

  
       
        if($maxBC != null){
            $cd=explode("/", $maxBC->tt_ref);
            $tt_ref=$cd[0];


            if(date('Y',strtotime($maxBC->created)) == date("Y")){
                $chk = substr("$tt_ref",-3); //0001

                if(intval($chk) >= 1){
                    $chk = intval($chk)+1;
                    $gen = substr("0000$chk",-3);
                }else{
                    $gen = substr("00001",-3);
                }

            }else{
                $gen = substr("00001",-3);
            }
        }else{
            $gen = substr("00001",-3);
        }
        $nextId = $prefix.$gen."/".date("y");
        return $nextId;
    }
}
