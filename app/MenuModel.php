<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuModel extends Model
{
    protected $table = 'tb_menu';
    protected $primaryKey = 'id';
    protected $fillable = ['_id','position','name','icon','url','status'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamp = false;


    
}
