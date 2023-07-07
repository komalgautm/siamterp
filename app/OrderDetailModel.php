<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetailModel extends Model
{
    protected $table = 'orders_detail';
    protected $primaryKey = 'id';
    protected $fillable = ['order_id','itf_id','qty','unitcount_id','ean_id','ean_qty','net_weight','new_weight','maxcbm','maxpallet','number_box','nw','gw_weight','cbm','pallet','price_allocation','price_pallet_unit','itf_pallet_price','itf_clearance_price','itf_transport_price','itf_cost_price','itf_freight_rate','total_itf_cost','unit_price','profit','created','updated','profit2','net_weightNew','itf_cal_selling','itf_GW','ean_ppITF','itfQty','net_weight2','hpl_avg_weight','box_weight','box_pallet','itf_fx_price','fx_price'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
