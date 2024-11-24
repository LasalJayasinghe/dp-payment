<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    const FULL_PAYMENT = "FULL_PAYMENT";
    const ADVANCE_PAYMENT = "ADVANCE_PAYMENT";

    const TRANSACTION_SUCCESS = "TRANSACTION_SUCCESS";
    const TRANSACTION_FAILED = "TRANSACTION_FAILED";

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = auth()?->id();
        });
        static::updating(function ($model) {
            $model->updated_by = auth()?->id();
        });
    }

    public function requestRef():BelongsTo
    {
        return $this->belongsTo(Requests::class, 'request', 'id');
    }

    public function subRequestRef():BelongsTo
    {
        return $this->belongsTo(SubRequest::class, 'sub_request', 'id');
    }

    public function createdByRef(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedByRef(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
