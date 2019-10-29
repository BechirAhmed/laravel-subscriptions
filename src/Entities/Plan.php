<?php

namespace Sagitarius29\LaravelSubscriptions\Entities;

use Sagitarius29\LaravelSubscriptions\Plan as PlanBase;
use Sagitarius29\LaravelSubscriptions\Traits\HasSinglePrice;

class Plan extends PlanBase
{
    use HasSinglePrice;
}
