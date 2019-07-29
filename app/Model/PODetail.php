<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PODetail extends Model
{
    use SoftDeletes;
    
    protected $table = "po_detail";
}
