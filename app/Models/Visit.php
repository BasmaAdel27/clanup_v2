<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = ['user_id', 'group_id', 'date', 'ip', 'user_agent', 'visitable_id', 'visitable_type'];
    public $timestamps = false;
}