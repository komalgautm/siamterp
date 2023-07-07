<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClearanceModel extends Model
{
    protected $table = 'clearance';
    protected $primarykey ='id';
    protected $fillable =['id','vendor','charge','certificate','chamber','extras','status','created','updated'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
