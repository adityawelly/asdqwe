<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PADTL extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pa_params';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['ParamsId'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
	
	public function subbab()
    {
        return $this->belongsTo('App\Models\PASUB', 'SubbabId', 'SubbabId');
    }

}
