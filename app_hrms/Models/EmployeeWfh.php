<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmployeeWfh extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_wfh';

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
    protected $dates = ['created_at', 'updated_at', 'start_date', 'end_date', 'approved_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['diff_days'];

    /**
     * Accessor Diff Days
     *
     * @return void
     */
    public function getDiffDaysAttribute()
    {
        $diff_days = Carbon::parse($this->end_date)->diffInDays($this->start_date);

        return $diff_days+1;
    }

    //Relation goes here
    public function approved_by()
    {
        return $this->belongsTo('App\Models\Employee', 'approval_by', 'registration_number');
    }
	
	public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_no', 'registration_number')->withoutGlobalScopes();
    }

}
