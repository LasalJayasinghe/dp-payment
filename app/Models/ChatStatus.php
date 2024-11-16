<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatStatus extends Model
{

    protected $table = 'chat_status'; // Make sure this is the correct table name


    protected $fillable = [
        'request_id',
        'status',
    ];

    public function request()
    {
        return $this->belongsTo(Requests::class);
    }

}
