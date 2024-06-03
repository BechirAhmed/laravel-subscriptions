<?php

namespace Beksos\LaravelSubscriptions\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Beksos\LaravelSubscriptions\Contracts\PlanContract;
use Beksos\LaravelSubscriptions\Contracts\SubscriptionContact;
use Beksos\LaravelSubscriptions\Exceptions\SubscriptionErrorException;

class Subscription extends Model implements SubscriptionContact
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'plan_id', 'start_at', 'end_at', 'status'
    ];

    protected $dates = [
        'start_at', 'end_at',
    ];

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_INACTIVE = 'INACTIVE';
    public const STATUS_ON_TRIAL = 'ON_TRIAL';
    public const STATUS_CANCELLED = 'CANCELLED';

    public static function make(PlanContract $plan, Carbon $start_at, Carbon $end_at = null): Model
    {
        if (! $plan instanceof Model) {
            throw new SubscriptionErrorException('$plan must be '.Model::class);
        }

        return new self([
            'plan_id' => $plan->id,
            'start_at' => $start_at,
            'end_at' => $end_at,
        ]);
    }

    //mark subscription as ACTIVE
    public function activate()
    {
        if($this->status == self::STATUS_ACTIVE) {
            throw new SubscriptionErrorException('Subscription already activated');
        }
        if($this->status == self::STATUS_INACTIVE) {
            throw new SubscriptionErrorException('Subscription already expired');
        }
        return $this->update(['status' => self::STATUS_ACTIVE]);
    }

    public function scopeCurrent(Builder $q)
    {
        $today = now();

        return $q->where('status', '!=', 'INACTIVE')->where('start_at', '<=', $today)
            ->where(function ($query) use ($today) {
                $query->where('end_at', '>=', $today)->orWhereNull('end_at');
            });
    }

    public function scopeUnfinished(Builder $q)
    {
        $today = now();

        return $q->where('status', '!=', 'INACTIVE')->where(function ($query) use ($today) {
            $query->where('end_at', '>=', $today)->orWhereNull('end_at');
        });
    }

    public function getDaysLeft(): ?int
    {
        if ($this->isPerpetual()) {
            return null;
        }

        return now()->diffInDays($this->end_at);
    }

    public function isPerpetual(): bool
    {
        return $this->end_at == null;
    }

    public function getElapsedDays(): int
    {
        return now()->diffInDays($this->start_at);
    }

    public function getExpirationDate(): ?Carbon
    {
        return $this->end_at;
    }

    public function getStartDate(): Carbon
    {
        return $this->start_at;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function subscriber()
    {
        return $this->morphTo();
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(config('subscriptions.entities.plan'));
    }
}
