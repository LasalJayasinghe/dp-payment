<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubRequest extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = "PENDING";
    const STATUS_CHECKED = "CHECKED";
    const STATUS_WAITING_FOR_SIGNATURE = "WAITING_FOR_SIGNATURE";
    const STATUS_REJECTED = "REJECTED";
    const STATUS_APPROVED = "APPROVED";

    const PRIORITY_LOW = "LOW";
    const PRIORITY_NORMAL = "NORMAL";
    const PRIORITY_HIGH = "HIGH";

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

    public function checkedRef(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by', 'id');
    }
    public function signedRef(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by', 'id');
    }
    public function approvedRef(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
    public function requestRef():BelongsTo
    {
        return $this->belongsTo(Requests::class, 'request', 'id');
    }
    public function createdByRef(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function supplierRef(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }


    public function updatedByRef(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
