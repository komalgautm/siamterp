<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DistrictModel extends Model
{
    protected $table = 'district';
    protected $primarykey ='id';
    protected $fillable =['id,code,name_th,name_en,_id'];
}
