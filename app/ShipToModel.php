<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipToModel extends Model
{
    protected $table = 'shiptos';
    protected $primarykey ='id';
    protected $fillable =['id','name','client','tax_number,email','phone','address','notify_name','notify_tax_number','notify_phone','notify_email','notify_address','created','updated','code'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;



}


