<?php

namespace App\Models\ChildTests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildRosterUpdated extends Model
{
    //use HasFactory;
    protected $connection = 'db_child_test';
    protected $table = 'child_roster_updated';
}
