<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashAccountFeedLog extends Model
{
    use HasFactory, SoftDeletes;

    const CREDITED = "CREDITED";
    const DEBITED = "DEBITED";


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

}
