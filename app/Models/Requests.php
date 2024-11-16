<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    protected static function booted()
    {
        static::created(function ($request) {
            // When a new request is created, also create a corresponding entry in chat_status
            ChatStatus::create([
                'request_id' => $request->id,
                'status' => false, // default to "disabled"
            ]);
        });
    }
}
