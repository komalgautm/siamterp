<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SortingModel extends Model
{
    protected $table = 'sorting';
    protected $primaryKey = 'id';
    protected $fillable = ['receive','code','type','item','waste_qty','blue_crate','num_qty','transection_type','note','sorting_date','total_price','unit','balance'];
    // const CREATED_AT = 'created';
    // const UPDATED_AT = 'updated';
    public $timestamps = false;
}
