<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDetail extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_details';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['age'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    //Relation goes here
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    /**
     * Accessor age
     *
     * @return void
     */
    public function getAgeAttribute()
    {
        return Carbon::parse($this->date_of_birth)
        ->age.' Tahun';
    }
}