<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applier extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'applier';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
	
	public function employee_job()
    {
        return $this->hasMany('App\Models\JobTitle', 'job_id', 'id');
    }
	
	
	public function regional()
    {
        return $this->hasMany('App\Models\CompanyRegion', 'region_id', 'id');
    }
	
	public function jobber()
    {
        return $this->hasMany('App\Models\Jobs', 'id_job', 'id');
    }
	
	
    
    //Relation goes here
}