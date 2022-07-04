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
        return $this->hasMany(JobTitle::class, 'job_id', 'id');
    }

	public function regional()
    {
        return $this->hasMany(CompanyRegion::class, 'region_id', 'id');
    }

	public function jobber()
    {
        return $this->hasMany(Jobs::class, 'id_job', 'id');
    }


    // Relation goes here
}
