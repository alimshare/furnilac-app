<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Part extends Model
{
    use SoftDeletes;
    
    protected $table = "part";
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'part_number';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'char';
}
