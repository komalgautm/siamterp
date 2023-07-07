<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackingModel extends Model
{
    protected $table = 'packing';
    protected $primaryKey = 'id';
    protected $fillable = ['code','start','finish','number_staff','qty','sorting_id','sorting_qty','item','unit','cost_asl','po_price','wastage','wastage_weight','wastage_percent','cost','ean_cost','wages','created','updated'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
