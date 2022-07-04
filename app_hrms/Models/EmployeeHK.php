<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeHK extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_hks';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'employee_no';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_no', 'registration_number');
    }
}
