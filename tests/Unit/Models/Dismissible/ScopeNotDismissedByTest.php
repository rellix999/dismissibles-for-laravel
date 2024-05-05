<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Tests\Unit\Models\Dismissible;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use PHPUnit\Framework\Attributes\Test;
use Rellix\Dismissibles\Contracts\Dismisser;
use Rellix\Dismissibles\Models\Dismissal;
use Rellix\Dismissibles\Models\Dismissible;
use Rellix\Dismissibles\Models\TestDismisserTypeOne;
use Rellix\Dismissibles\Tests\BaseTestCase;

class ScopeNotDismissedByTest extends BaseTestCase
{
    private Dismisser $dismisser;

    public function setUp(): void
    {
        parent::setUp();

        $this->dismisser = TestDismisserTypeOne::factory()->create();
    }

    #[Test]
    public function it_returns_dismissibles_which_are_active_in_the_past(): void
    {
        Dismissible::factory()->active()->create();

        $actualValue = Dismissible::notDismissedBy($this->dismisser)->get();

        $this->assertNotEmpty($actualValue);
    }

    #[Test]
    public function it_returns_dismissibles_which_are_active_in_the_future(): void
    {
        $now = CarbonImmutable::now();

        Dismissible::factory()
            ->active(CarbonPeriod::create($now->addDay(), $now->addWeek()))
            ->create();

        $actualValue = Dismissible::notDismissedBy($this->dismisser)->get();

        $this->assertNotEmpty($actualValue);
    }

    #[Test]
    public function it_returns_dismissibles_when_dismissed_in_the_past(): void
    {
        $dismissible = Dismissible::factory()->create();

        Dismissal::factory()
            ->for($this->dismisser, 'dismisser')
            ->for($dismissible)
            ->create([
                'dismissed_until' => Carbon::yesterday(),
            ]);

        $actualValue = Dismissible::notDismissedBy($this->dismisser)->get();

        $this->assertNotEmpty($actualValue);
    }

    #[Test]
    public function it_returns_dismissibles_when_dismissed_in_the_future_by_someone_else(): void
    {
        $dismissible = Dismissible::factory()->create();

        Dismissal::factory()
            ->for($dismissible)
            ->create([
                'dismissed_until' => Carbon::yesterday(),
            ]);

        $actualValue = Dismissible::notDismissedBy($this->dismisser)->get();

        $this->assertNotEmpty($actualValue);
    }

    #[Test]
    public function it_does_not_return_dismissibles_when_dismissed_until_future_date_time(): void
    {
        $dismissible = Dismissible::factory()->create();

        Dismissal::factory()
            ->for($this->dismisser, 'dismisser')
            ->for($dismissible)
            ->create([
                'dismissed_until' => Carbon::tomorrow(),
            ]);

        $actualValue = Dismissible::notDismissedBy($this->dismisser)->get();

        $this->assertEmpty($actualValue);
    }

    #[Test]
    public function it_does_not_return_dismissibles_when_dismissed_until_forever(): void
    {
        $dismissible = Dismissible::factory()->create();

        Dismissal::factory()
            ->for($this->dismisser, 'dismisser')
            ->for($dismissible)
            ->create([
                'dismissed_until' => null,
            ]);

        $actualValue = Dismissible::notDismissedBy($this->dismisser)->get();

        $this->assertEmpty($actualValue);
    }
}
