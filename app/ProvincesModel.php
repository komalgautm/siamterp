<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProvincesModel extends Model
{
    protected $table = 'provinces';
    protected $primaryKey = 'id';
    protected $fillable = ['id','code','name_th','name_en','_id'];
}
