<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmployeeResign extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'v_quota_resign';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'start_date', 'end_date'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    //protected $appends = ['diff_days'];

    /**
     * Accessor Diff Days
     *
     * @return void
     */

}
