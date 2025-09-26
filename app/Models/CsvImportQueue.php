<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvImportQueue extends Model
{
    use HasFactory;

    protected $guarded = ['id']; 
}
