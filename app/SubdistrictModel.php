<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubdistrictModel extends Model
{
    protected $table ='sub-district';
    protected $primarykey ='id';
    protected $fillable =['id,zipcode,name_th,name_en,_id'];
}
