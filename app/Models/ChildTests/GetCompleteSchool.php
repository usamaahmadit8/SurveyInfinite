<?php

namespace App\Models\ChildTests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetCompleteSchool extends Model
{
    //use HasFactory;
    protected $connection = 'db_child_test';
    protected $table = 'get_school_count';
}
