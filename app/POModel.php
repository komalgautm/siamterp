<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class POModel extends Model
{
    protected $table = 'pos';
    protected $primaryKey = 'id';
    protected $fillable = ['code','users','vendor','items','status','staff_press','paid_date','paid_by','staff_name','pickup_date','delivery_date','receive_date','return_date','created','updated'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;

    function getItems()
    {
        return $this->hasMany('App\AddItemModel','items');
    }

    public static function getCode()
    {
        // $maxId = DB::table('pos')->orderBy('code','desc')->first();
        // $maxId = DB::table('pos')->whereNull('code')->orderBy('id','desc')->first();
        $maxBC = DB::table('pos')->select('code')->orderBy('code','desc')->limit(1)->first();
        $prefix = 'PO';

		/*if($maxBC != null){
            $cut = substr("$maxBC->code",2); //2020030001
            $cut2 = substr("$cut",0,-4); //202003
            
            if(date('Ym',strtotime($cut2)) == date("Ym")){
                $chk = substr("$maxBC->code",-4); //0001

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
        $nextId = $prefix.date("Ym").$gen;
        return $nextId;
		*/

        if($maxBC != null)
		{
            $cut = substr("$maxBC->code",2); //2020030001
            $cut2 = substr("$cut",0,-3); //202003
            
            if($cut2 == date("Ym")){
                $chk = substr("$maxBC->code",-3); //0001

                if(intval($chk) >= 1)
				{
                    $chk = intval($chk)+1;
                    $gen = substr("0000$chk",-3);
                }
				else
				{
                    $gen = substr("00001",-3);
                }

            }else{
                $gen = substr("00001",-3);
            }
        }else
		{
            $gen = substr("00001",-3);
        }
        $nextId = $prefix.date("Ym").$gen;
        return $nextId;
		
    }

}
