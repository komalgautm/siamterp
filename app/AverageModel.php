<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AverageModel extends Model
{
    protected $table = 'average_lot';
    protected $primarykey ='id';
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
