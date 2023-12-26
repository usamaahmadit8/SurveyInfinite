<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class section_2 extends Model
{
    use HasFactory;
    protected $connection = 'db_parent_engagement';
    protected $table = 'section_2';
    public function setInterviewDateAttribute($value)
    {
        // Assuming $value is a string in the format '2023-10-13-02:10:00:00'
        $this->attributes['interview_date'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
    }
}
