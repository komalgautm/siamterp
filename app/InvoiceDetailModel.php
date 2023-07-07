<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetailModel extends Model
{
    protected $table = 'invoices_detail';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
