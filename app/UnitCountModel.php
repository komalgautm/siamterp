<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class UnitCountModel extends Model
{
    protected $table = 'unit_count';
    protected $primaryKey = 'id';
    protected $fillable = ['name','status','sort','created','updated'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
