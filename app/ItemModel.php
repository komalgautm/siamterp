<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $fillable = ['name_th','name_en', 'hs_code', 'hs_name', 'type','barcode','width','length','height','cbm','weight','minload','box_pallet','cost','unit_cost','wrap_cost','wrap_weight','status','sort','created','updated'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;

    // function qty()
    // {
    //     return $this->hasMany('App\InventoryModel','item','id');
    // }

    public static function getBarcode($id)
    {
        // $maxId = DB::table('items')->orderBy('barcode','desc')->first();
        $maxId = DB::table('items')->whereNull('barcode')->orderBy('id','desc')->first();
        $maxBC = DB::table('items')->select('barcode')->orderBy('barcode','desc')->limit(1)->first();
        $prefix = 'I';

            $chk = substr("$maxBC->barcode",-4); //0001
            if(intval($chk) >= 1){
                $chk = intval($chk)+1;
                $gen = substr("0000$chk",-4);
            }elseif(intval($chk) >= 10){
                $chk = intval($chk)+1;
                $gen = substr("000$chk",-3);
            }elseif(intval($chk) >= 100){
                $chk = intval($chk)+1;
                $gen = substr("00$chk",-2);
            }elseif(intval($chk) >= 1000){
                $chk = intval($chk)+1;
                $gen = substr("0$chk",-1);
            }else{
                $gen = substr("00001",-4);
            }
            
        $nextId = $prefix.$gen;
        DB::table('items')->where('id',$id)->update(['barcode' => $nextId]);
    }
}
