<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRetirement extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_retirements';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    //Relation goes here
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee')->withoutGlobalScopes();
    }
}
