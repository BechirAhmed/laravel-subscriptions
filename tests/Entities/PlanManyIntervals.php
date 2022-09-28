<?php

namespace Beksos\LaravelSubscriptions\Tests\Entities;

use Beksos\LaravelSubscriptions\Plan;
use Beksos\LaravelSubscriptions\Traits\HasManyIntervals;

class PlanManyIntervals extends Plan
{
    use HasManyIntervals;
}
