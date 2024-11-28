<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashAccount extends Model
{
    use HasFactory, SoftDeletes;

    const ACTIVE = "ACTIVE";
    const INACTIVE = "INACTIVE";

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

    function detailsRef(): HasMany
    {
        return $this->hasMany(CashAccountFeedLog::class, 'cash_account', 'id');
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
