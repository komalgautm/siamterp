<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class PoCodeModel extends Model
{
  static function code($packing_detail_id)
  {
	  return DB::table("pol as pl")->join('packing_detail as pd','pd.id','=','pl.packing_detail_id')->join('packing  as pk','pk.id','=','pd.packing')->join('sorting as sr','sr.id','=','pk.sorting_id')->where('pl.packing_detail_id',$packing_detail_id)->first()->code;
	
  }
  
 static function ean_uc($packing_detail_id)
  {
	  return DB::table("pol as pl")->select('pd.ean_uc')->join('packing_detail as pd','pd.id','=','pl.packing_detail_id')->join('packing  as pk','pk.id','=','pd.packing')->where('pl.packing_detail_id',$packing_detail_id)->first()->ean_uc;	
  }
  /*static function ean_ucfromTransaction($packing_detail_id)
  {
	  return DB::table("pol as pl")->select('pd.ean_uc')->join('packing_detail as pd','pd.id','=','pl.packing_detail_id')->join('packing  as pk','pk.id','=','pd.packing')->where('pl.packing_detail_id',$packing_detail_id)->first()->ean_uc;	
  }*/

 
}
