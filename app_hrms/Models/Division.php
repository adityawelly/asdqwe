<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'divisions';

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
    
    public function departments()
    {
        return $this->hasMany('App\Models\Department');
    }
}