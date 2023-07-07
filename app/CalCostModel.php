<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalCostModel extends Model
{
    protected $table = 'cal_cost';
    protected $primarykey ='id';
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
