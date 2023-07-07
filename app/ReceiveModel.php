<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiveModel extends Model
{
    protected $table = 'receiving';
    protected $primaryKey = 'id';
    // const CREATED_AT = 'created';
    // const UPDATED_AT = 'updated';
    public $timestamps = false;
}
