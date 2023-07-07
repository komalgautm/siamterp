<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    protected $table = 'payment_transactions';
    protected $primaryKey = 'id';
    protected $fillable = ['created_at','transaction','numbers','amount','notes','updated_at','vendor'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
  
  public static function getCode()
    {
        // $maxId = DB::table('pos')->orderBy('code','desc')->first();
        // $maxId = DB::table('pos')->whereNull('code')->orderBy('id','desc')->first();
        $maxBC = DB::table('payment_transactions')->select('numbers')->where('is_dn','yes')->orderBy('numbers','desc')->limit(1)->first();
        $prefix = 'DN';

        if($maxBC != null){
            $cut = substr("$maxBC->numbers",2); //2020030001
            $cut2 = substr("$cut",0,-4); //202003
            
            if($cut2 == date("Ym")){
                $chk = substr("$maxBC->numbers",-3); //0001

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

}
