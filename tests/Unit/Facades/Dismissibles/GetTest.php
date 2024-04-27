<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Tests\Unit\Facades\Dismissibles;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use PHPUnit\Framework\Attributes\Test;
use ThijsSchalk\LaravelDismissibles\Facades\Dismissibles;
use ThijsSchalk\LaravelDismissibles\Models\Dismissible;
use ThijsSchalk\LaravelDismissibles\Tests\BaseTestCase;

class GetTest extends BaseTestCase
{
    private readonly string $name;

    public function setUp(): void
    {
        parent::setUp();

        $this->name = 'test';
    }

    #[Test]
    public function it_returns_null_when_name_does_not_exist()
    {
        $actualResult = Dismissibles::get($this->name);

        $this->assertNull($actualResult);
    }

    #[Test]
    public function it_returns_null_before_active_period()
    {
        $now = CarbonImmutable::now();

        Dismissible::factory()
            ->active(CarbonPeriod::create($now->addDay(), $now->addWeek()))
            ->create([
                'name' => $this->name,
            ]);

        $actualResult = Dismissibles::get($this->name);

        $this->assertNull($actualResult);
    }

    #[Test]
    public function it_returns_dismissible_during_active_period_when_active_until_is_set()
    {
        $now = CarbonImmutable::now();

        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subMinute(), $now->addDay()))
            ->create([
                'name' => $this->name,
        ]);

        $actualResult = Dismissibles::get($this->name);

        $this->assertTrue($actualResult->is($dismissible));
    }

    #[Test]
    public function it_returns_dismissible_during_active_period_when_active_until_is_null()
    {
        $now = CarbonImmutable::now();

        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subMinute()))
            ->create([
                'name' => $this->name,
            ]);

        $actualResult = Dismissibles::get($this->name);

        $this->assertTrue($actualResult->is($dismissible));
    }

    #[Test]
    public function it_returns_null_after_active_period()
    {
        $now = CarbonImmutable::now();

        Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeek(), $now->subDay()))
            ->create([
                'name' => $this->name,
        ]);

        $actualResult = Dismissibles::get($this->name);

        $this->assertNull($actualResult);
    }
}
