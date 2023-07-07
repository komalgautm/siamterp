<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ITFModel extends Model
{
    protected $table = 'itf';
    protected $primaryKey = 'id';
    protected $fillable = ['code','name','user','total_weight','new_weight','maxcbm','maxminload','maxbox_pallet','status','created','updated'];
    // const CREATED_AT = 'created';
    // const UPDATED_AT = 'updated';
    public $timestamps = false;
}
