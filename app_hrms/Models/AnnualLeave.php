<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnualLeave extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'annual_leaves';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    //Relation Goes Here
}
