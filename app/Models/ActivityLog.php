<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['activity_type', 'details'];
    public $timestamps = true; // use created_at / updated_at
}

