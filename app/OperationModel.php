<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OperationModel extends Model
{
    protected $table = 'operation';
    protected $primarykey ='id';
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
