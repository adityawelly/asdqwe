<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PASUB extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pa_subbab';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['SubbabId'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
	
	public function grade()
    {
       return $this->belongsTo('App\Models\GradeTitle', 'GradeId', 'id');
    }
	
	public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_no', 'registration_number')->withoutGlobalScopes();
    }

}
