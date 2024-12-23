<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $fillable = ['request_id', 'sub_request', 'file_path', 'message'];

    protected $table = 'files'; // Make sure this is the correct table name
}
