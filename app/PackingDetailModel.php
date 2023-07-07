<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackingDetailModel extends Model
{
    protected $table = 'packing_detail';
    protected $primaryKey = 'id';
    protected $fillable = ['packing','product','number_pack','product_weight','cost_packaging','wrap_cost','plus_cost','created','updated'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
