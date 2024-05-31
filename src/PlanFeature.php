<?php

namespace Beksos\LaravelSubscriptions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class PlanFeature extends Model
{
    protected $table = 'plan_features';

    protected $fillable = [
        'code', 'value', 'sort_order', 'is_consumable',
    ];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('sort_order', 'asc');
        });
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(config('subscriptions.entities.plan'));
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
