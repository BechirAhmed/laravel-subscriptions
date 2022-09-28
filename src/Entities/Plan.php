<?php

namespace Beksos\LaravelSubscriptions\Entities;

use Beksos\LaravelSubscriptions\Plan as PlanBase;
use Beksos\LaravelSubscriptions\Traits\HasSingleInterval;

class Plan extends PlanBase
{
    use HasSingleInterval;
}
