<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employees';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['year_of_service'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Accessor year of work
     *
     * @return void
     */
    public function getYearOfServiceAttribute()
    {
        return Carbon::parse($this->date_of_work)
            ->diff(Carbon::now())
            ->format('%y tahun, %m bulan, %d hari');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('active', function(Builder $builder){
            $builder->doesntHave('employee_retirement');
        });
    }
    
    //Relation goes here
    public function division()
    {
        return $this->belongsTo('App\Models\Division');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    public function grade_title()
    {
        return $this->belongsTo('App\Models\GradeTitle');
    }

    public function level_title()
    {
        return $this->belongsTo('App\Models\LevelTitle');
    }

    public function job_title()
    {
        return $this->belongsTo('App\Models\JobTitle');
    }

    public function company_region()
    {
        return $this->belongsTo('App\Models\CompanyRegion');
    }

    public function user()
    {
        return $this->hasOne('App\User')->withTrashed();
    }

    public function employee_bank_accounts()
    {
        return $this->hasMany('App\Models\EmployeeBankAccount');
    }

    public function employee_detail()
    {
        return $this->hasOne('App\Models\EmployeeDetail');
    }

    public function employee_salary()
    {
        return $this->hasOne('App\Models\EmployeeSalary');
    }

    public function employee_retirement()
    {
        return $this->hasOne('App\Models\EmployeeRetirement');
    }

    public function employee_leaves()
    {
        return $this->hasMany('App\Models\EmployeeLeave', 'employee_no', 'registration_number');
    }

    // public function approved_employee_leaves()
    // {
    //     return $this->hasMany('App\Models\EmployeeLeave', 'id', 'approved_by');
    // }

    public function trainings()
    {
        return $this->belongsToMany('App\Models\Training', 'employees_trainings');
    }

    public function superior()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'direct_superior');
    }
	
	public function director()
    {
        return $this->hasOne('App\Models\EmployeeDir', 'id', 'direktur_id');
    }
	
	 public function created_by()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'created_by');
    }

    public function isSuperior()
    {
        return $this->query()->where('direct_superior', '=', $this->id)->count() > 0;
    }

    public function hari_kerja()
    {
        return $this->hasOne('App\Models\EmployeeHK', 'employee_no', 'registration_number');
    }
}