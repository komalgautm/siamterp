<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetupModel extends Model
{
    protected $table = 'setups';
    protected $primaryKey = 'id';
    protected $fillable = ['type','item','unit','qty','product','avg_weight','weight','wrap_weight','created','updated'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
