<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HCApproval extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 't_job_hc_approval';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'Id';

    //Relation goes here
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'EmployeeId', 'id')->withoutGlobalScopes();
    }
}
