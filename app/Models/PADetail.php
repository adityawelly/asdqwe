<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PADetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pa_dtl';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['PaParamsId'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    //protected $dates = ['CreatedDate'];

}
