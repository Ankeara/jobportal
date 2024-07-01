<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;
    public function job()
    {
          return $this->belongsTo(Jobs::class, 'job_id');
    }
    public function user()
    {
          return $this->belongsTo(User::class);
    }

    public function employer()
    {
          return $this->belongsTo(User::class,'employer_id');
    }
}