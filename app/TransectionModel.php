<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransectionModel extends Model
{
    protected $table = 'transection';
    protected $primaryKey = 'id';
    protected $fillable = ['code','transection_type','type','item_id','unit','qty','transection_date'];
    // const CREATED_AT = 'created';
    // const UPDATED_AT = 'updated';
    public $timestamps = false;
}
