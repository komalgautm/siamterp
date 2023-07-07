<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class QuotationModel extends Model
{
    protected $table = 'quotations';
    protected $primaryKey = 'id';
    protected $fillable = ['code','user_id','client_id','shipto_id','airport_id','airline_id','clearance_id','cerrency_id','ex_rate','markup_rate','rebate','select_pallet','select_chamber','clearance','chamber','clearance_price','transport','transport_price','total_box','total_nw','total_gw','total_cbm','palletized','palletized_price','total_freight','total_fob','total_pro_before_rebate','total_pro_after_rebate','total_pro_percent','status','ship_date','created','updated','total_cnf','freights','markup_rateCal'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;

    public static function getQCode()
    {
        // $maxId = DB::table('pos')->orderBy('code','desc')->first();
        // $maxId = DB::table('pos')->whereNull('code')->orderBy('id','desc')->first();
        $maxBC = DB::table('quotations')->select('code')->orderBy('code','desc')->orderBy('created','desc')->limit(1)->first();
        $prefix = 'Q-';

        if($maxBC != null){
            $cut = substr("$maxBC->code",2); //2020030001
            $cut2 = substr("$cut",0,-3); //202003
            
            if($cut2== date("Ym")){
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
        $nextId = $prefix.date("Ym").$gen;
        return $nextId;
    }

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
        $maxBC = DB::table('orders')->select('inv_no')->orderBy('inv_no','desc')->orderBy('created','desc')->limit(1)->first();
        $prefix = 'INV-';

        if($maxBC != null){
            $cut = substr("$maxBC->inv_no",4); //2020030001
            $cut2 = substr("$cut",0,-3); //202003
            
            if($cut2 == date("Ym")){
                $chk = substr("$maxBC->inv_no",-3); //0001

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
        $nextId = $prefix.date("Ym").$gen;
        return $nextId;
    }

    public static function getTTRef($client){
        $maxBC = DB::table('orders')->select('tt_ref','created')->where('client_id',$client)->orderBy('tt_ref','desc')->orderBy('created','desc')->limit(1)->first();
        $prefix = 'MAF';
        if($maxBC != null){
            if(date('Y',strtotime($maxBC->created)) == date("Y")){
                $chk = substr("$maxBC->tt_ref",-4); //0001

                if(intval($chk) >= 1){
                    $chk = intval($chk)+1;
                    $gen = substr("0000$chk",-4);
                }else{
                    $gen = substr("00001",-4);
                }

            }else{
                $gen = substr("00001",-4);
            }
        }else{
            $gen = substr("00001",-4);
        }
        $nextId = $prefix.$gen;
        return $nextId;
    }
}
