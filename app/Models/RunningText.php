<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RunningText extends Model
{
    protected $table = 'running_texts';
    protected $fillable = ['text', 'is_active'];
}