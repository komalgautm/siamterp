<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AirportModel extends Model
{
    protected $table = 'airports';
    protected $primarykey ='id';
    protected $fillable =['id','country','city','airport_code','status','sort','created','updated'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
