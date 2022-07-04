<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'departments';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    //Relation goes here
    public function employees()
    {
        return $this->hasMany('App\Models\Employee');
    }

    public function division()
    {
        return $this->belongsTo('App\Models\Division');
    }

    public function manager()
    {
        return $this->belongsTo('App\Models\Employee', 'head_manager', 'id');
    }

    public function supervisor()
    {
        return $this->belongsTo('App\Models\Employee', 'head_supervisor', 'id');
    }
}