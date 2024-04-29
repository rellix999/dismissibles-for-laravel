<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Tests\Unit\Models\Dismissible;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use PHPUnit\Framework\Attributes\Test;
use Rellix\Dismissibles\Models\Dismissible;
use Rellix\Dismissibles\Tests\BaseTestCase;

class ScopeActiveFromTest extends BaseTestCase
{
    #[Test]
    public function it_does_not_return_the_dismissible_before_active_period()
    {
        $now = CarbonImmutable::now();

        Dismissible::factory()
            ->active(CarbonPeriod::create($now->addDay(), $now->addWeek()))
            ->create();

        $this->assertEmpty(Dismissible::active()->get());
    }

    #[Test]
    public function it_returns_the_dismissible_on_active_period_start()
    {
        $now = CarbonImmutable::now();

        Dismissible::factory()
            ->active(CarbonPeriod::create($now, $now->addMinute()))
            ->create();

        $this->assertNotEmpty(Dismissible::active()->get());
    }

    #[Test]
    public function it_returns_the_dismissible_during_active_period_when_active_until_is_set()
    {
        $now = CarbonImmutable::now();

        Dismissible::factory()
            ->active(CarbonPeriod::create($now->subMinute(), $now->addMinute()))
            ->create();

        $this->assertNotEmpty(Dismissible::active()->get());
    }

    #[Test]
    public function it_returns_the_dismissible_during_active_period_when_active_until_is_null()
    {
        $now = CarbonImmutable::now();

        Dismissible::factory()
            ->active(CarbonPeriod::create($now->subMinute()))
            ->create();

        $this->assertNotEmpty(Dismissible::active()->get());
    }

    #[Test]
    public function it_does_not_return_the_dismissible_after_active_period()
    {
        $now = CarbonImmutable::now();

        Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeek(), $now->subDay()))
            ->create();

        $this->assertEmpty(Dismissible::active()->get());
    }
}
