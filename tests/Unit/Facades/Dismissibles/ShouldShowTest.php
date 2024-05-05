<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Tests\Unit\Facades\Dismissibles;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use PHPUnit\Framework\Attributes\Test;
use Rellix\Dismissibles\Contracts\Dismisser;
use Rellix\Dismissibles\Facades\Dismissibles;
use Rellix\Dismissibles\Models\Dismissal;
use Rellix\Dismissibles\Models\Dismissible;
use Rellix\Dismissibles\Models\TestDismisserTypeOne;
use Rellix\Dismissibles\Tests\BaseTestCase;

class ShouldShowTest extends BaseTestCase
{
    private readonly Dismisser $dismisser;

    public function setUp(): void
    {
        parent::setUp();

        $this->dismisser = TestDismisserTypeOne::factory()->create();
    }

    #[Test]
    public function it_returns_false_when_dismissible_name_does_not_exist()
    {
        $actualValue = Dismissibles::shouldBeVisible('test', $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_before_active_period_not_dismissed()
    {
        $now = CarbonImmutable::now();

        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->addDay(), $now->addWeek()))
            ->create();

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_before_active_period_dismissed_in_past()
    {
        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->addWeek(), $now->addWeeks(2)))
            ->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => $now->subDay(),
            ]);

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_before_active_period_dismissed_in_future()
    {
        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->addWeek(), $now->addWeeks(2)))
            ->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => $now->addDay(),
            ]);

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_before_active_period_dismissed_forever()
    {
        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->addWeek(), $now->addWeeks(2)))
            ->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => null,
            ]);

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_true_on_active_period_start_not_dismissed()
    {
        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now, $now->addWeeks(2)))
            ->create();

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertTrue($actualValue);
    }

    #[Test]
    public function it_returns_true_on_active_period_start_dismissed_in_past()
    {
        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now, $now->addWeeks(2)))
            ->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => $now->subDay(),
            ]);

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertTrue($actualValue);
    }

    #[Test]
    public function it_returns_false_on_active_period_start_dismissed_in_future()
    {
        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now, $now->addWeeks(2)))
            ->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => $now->addDay(),
            ]);

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_true_during_active_period_not_dismissed()
    {
        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subDay(), $now->addDay()))
            ->create();

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertTrue($actualValue);
    }

    #[Test]
    public function it_returns_true_during_active_period_dismissed_in_past()
    {
        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeek(), $now->addWeeks(2)))
            ->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => $now->subDay(),
            ]);

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertTrue($actualValue);
    }

    #[Test]
    public function it_returns_false_during_active_period_dismissed_in_future()
    {
        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeek(), $now->addWeeks(2)))
            ->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => $now->addDay(),
            ]);

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_during_active_period_dismissed_forever()
    {
        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeek(), $now->addWeeks(2)))
            ->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => null,
            ]);

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_after_active_period_not_dismissed()
    {
        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subDay(), $now->subSecond()))
            ->create();

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_after_active_period_dismissed_in_past()
    {
        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeeks(2), $now->subWeek()))
            ->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => $now->subDay(),
            ]);

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_after_active_period_dismissed_in_future()
    {
        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeeks(2), $now->subWeek()))
            ->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => $now->addDay(),
            ]);

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_after_active_period_dismissed_forever()
    {
        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeeks(2), $now->subWeek()))
            ->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => null,
            ]);

        $actualValue = Dismissibles::shouldBeVisible($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }
}
