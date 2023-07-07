<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankModel extends Model
{
    protected $table = 'banks';
    protected $primarykey ='id';
    protected $fillable =['id','name','bank_code','status','branch','created_at','updated_at','account','account_type'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
