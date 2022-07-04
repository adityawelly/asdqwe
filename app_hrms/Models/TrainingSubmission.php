<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TrainingSubmission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'training_submissions';

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
    protected $dates = ['created_at', 'updated_at', 'start_date', 'end_date'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('active', function(Builder $builder){
            $builder->where('status', '!=', 50);
        });
    }

    //Relation goes here
    public function employees()
    {
        return $this->belongsToMany('App\Models\Employee', 'employees_training_submissions')->withoutGlobalScopes();
    }

    public function submitted_by()
    {
        return $this->belongsTo('App\Models\Employee', 'submit_by', 'id')->withoutGlobalScopes();
    }
    
}
