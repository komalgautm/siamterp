<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreightModel extends Model
{
    protected $table = 'freight';
    protected $primarykey ='id';
    protected $fillable =['id','vendor','destination','airline','currency','rate_45','rate_100','rate_250','rate_500','rate_1000','rate_2000','nego_rate_45','nego_rate_100','nego_rate_250','nego_rate_500','nego_rate_1000','nego_rate_2000','status','sort','created','updated'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
