<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AirlineModel extends Model
{
    protected $table = 'airline';
    protected $primarykey ='id';
    protected $fillable =['id','airline','airline_code','status','sort','created','updated'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
