<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ListPKWT extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'list_pkwt';

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
    protected $dates = ['created_at', 'updated_at'];
	
	public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id', 'id')->withoutGlobalScopes();
    }
	
	public function job()
    {
        return $this->belongsTo('App\Models\JobTitle', 'job_title_id', 'job_title_code');
    }


}
