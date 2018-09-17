<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'name', 'rate', 'rate_type', 'currency', 
        'description', 'started_at', 'archive', 'archived_at', 'currency'
    ];

    public function owner()
    {
        return $this->belongsTo(Customer::class, 'customer_id')->select(['id', 'name', 'email']);
    }
}
