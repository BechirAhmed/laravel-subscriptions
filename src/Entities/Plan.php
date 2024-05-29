<?php

namespace Beksos\LaravelSubscriptions\Entities;

use Beksos\LaravelSubscriptions\Plan as PlanBase;
use Beksos\LaravelSubscriptions\Traits\HasSingleInterval;
use Beksos\LaravelSubscriptions\Traits\HasManyIntervals;

class Plan extends PlanBase
{
    use HasSingleInterval;
    use HasManyIntervals;
}
