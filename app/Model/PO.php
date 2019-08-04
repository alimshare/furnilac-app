<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PO extends Model
{
    use SoftDeletes;
    
    protected $table = "po";
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'po_number';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'char';
}
