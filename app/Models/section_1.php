<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class section_1 extends Model
{
    use HasFactory;
    protected $connection = 'db_parent_engagement';
    protected $table = 'section_1';
}
