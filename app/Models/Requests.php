<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requests extends Model
{

    const ADVANCE_PAYMENT = "ADVANCE_PAYMENT";
    const FULL_PAYMENT = "FULL_PAYMENT";

    const TYPE_LOCAL = "LOCAL";
    const TYPE_FOREIGN  = "FOREIGN";

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

    public function subRequestRef(): HasMany
    {
        return $this->hasMany(SubRequest::class, 'id', 'request');
    }

    public function transactionRef(): HasMany
    {
        return $this->hasMany(Transaction::class, 'id', 'request');
    }
}
