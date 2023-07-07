<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceCancelModel extends Model
{
    protected $table = 'invoices_cancel';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
