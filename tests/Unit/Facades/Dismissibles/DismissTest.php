<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Tests\Unit\Facades\Dismissibles;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use PHPUnit\Framework\Attributes\Test;
use Rellix\Dismissibles\Concerns\Dismiss;
use Rellix\Dismissibles\Facades\Dismissibles;
use Rellix\Dismissibles\Models\Dismissible;
use Rellix\Dismissibles\Models\TestDismisserTypeOne;
use Rellix\Dismissibles\Tests\BaseTestCase;

class DismissTest extends BaseTestCase
{
    private readonly Dismissible $dismissible;
    private readonly TestDismisserTypeOne $dismisser;

    public function setUp(): void
    {
        parent::setUp();

        $this->dismissible = Dismissible::factory()->active()->create();
        $this->dismisser = TestDismisserTypeOne::factory()->create();
    }

    #[Test]
    public function it_returns_null_before_active_period()
    {
        $now = CarbonImmutable::now();

        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeek(), $now->subDay()))
            ->create();

        $actualValue = Dismissibles::dismiss($dismissible->name, $this->dismisser);

        $this->assertNull($actualValue);
    }

    #[Test]
    public function it_returns_an_object_during_active_period()
    {
        $actualValue = Dismissibles::dismiss($this->dismissible->name, $this->dismisser);

        $this->assertIsObject($actualValue);
    }

    #[Test]
    public function it_returns_a_dismiss_object_during_active_period()
    {
        $actualValue = Dismissibles::dismiss($this->dismissible->name, $this->dismisser);

        $this->assertInstanceOf(Dismiss::class, $actualValue);
    }

    #[Test]
    public function the_dismiss_object_has_the_correct_dismisser()
    {
        $actualValue = Dismissibles::dismiss($this->dismissible->name, $this->dismisser);

        $this->assertTrue($actualValue->dismisser->is($this->dismisser));
    }

    #[Test]
    public function the_dismiss_object_has_the_correct_dismissible()
    {
        $actualValue = Dismissibles::dismiss($this->dismissible->name, $this->dismisser);

        $this->assertTrue($actualValue->dismissible->is($this->dismissible));
    }

    #[Test]
    public function it_returns_null_after_active_period()
    {
        $now = CarbonImmutable::now();

        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeek(), $now->subDay()))
            ->create();

        $actualValue = Dismissibles::dismiss($dismissible->name, $this->dismisser);

        $this->assertNull($actualValue);
    }
}
