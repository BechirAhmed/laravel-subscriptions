<?php

namespace Sagitarius29\LaravelSubscriptions\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sagitarius29\LaravelSubscriptions\Entities\Plan;
use Sagitarius29\LaravelSubscriptions\Entities\PlanConsumable;
use Sagitarius29\LaravelSubscriptions\Entities\PlanFeature;
use Sagitarius29\LaravelSubscriptions\Tests\TestCase;

class PlanFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_register_a_feature()
    {
        $firstFeature = PlanFeature::make(
            'foo',
            true,
            1
        );

        $secondFeature = PlanConsumable::make(
            'bar',
            5,
            2
        );

        $thirdFeature = PlanConsumable::make(
            'baz',
            1, // is consumable
            2
        );

        $plan = factory(Plan::class)->create();
        $plan->addFeature($firstFeature);
        $plan->addFeature($secondFeature);
        $plan->addFeature($thirdFeature);

        $this->assertEquals('foo', $firstFeature->getCode());
        $this->assertEquals($plan->id, $firstFeature->plan->id);
        $this->assertTrue($firstFeature->getValue());
        $this->assertNotTrue($firstFeature->is_consumable);

        $this->assertTrue($secondFeature->is_consumable);

        $this->assertTrue($thirdFeature->is_consumable);
    }
}
