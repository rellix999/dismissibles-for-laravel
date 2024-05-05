<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Tests\Unit\Facades\Dismissibles;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\Attributes\Test;
use Rellix\Dismissibles\Facades\Dismissibles;
use Rellix\Dismissibles\Models\Dismissal;
use Rellix\Dismissibles\Models\Dismissible;
use Rellix\Dismissibles\Models\TestDismisserTypeOne;
use Rellix\Dismissibles\Tests\BaseTestCase;

class GetAllForTest extends BaseTestCase
{
    #[Test]
    public function it_returns_an_empty_collection_when_there_are_no_dismissibles()
    {
        $dismisser = TestDismisserTypeOne::factory()->create();

        $actualResult = Dismissibles::getAllFor($dismisser);

        $this->assertInstanceOf(Collection::class, $actualResult);
        $this->assertCount(0, $actualResult);
    }

    #[Test]
    public function it_returns_no_inactive_dismissibles()
    {
        $dismisser = TestDismisserTypeOne::factory()->create();

        $now = CarbonImmutable::now();

        Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeek(), $now->subDay()))
            ->create();

        Dismissible::factory()
            ->active(CarbonPeriod::create($now->addDay(), $now->addWeek()))
            ->create();

        $actualResult = Dismissibles::getAllFor($dismisser);

        $this->assertInstanceOf(Collection::class, $actualResult);
        $this->assertCount(0, $actualResult);
    }

    #[Test]
    public function it_returns_active_dismissibles()
    {
        $dismisser = TestDismisserTypeOne::factory()->create();

        $now = CarbonImmutable::now();

        Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeek(), $now->addWeek()))
            ->create();

        $actualResult = Dismissibles::getAllFor($dismisser);

        $this->assertInstanceOf(Collection::class, $actualResult);
        $this->assertCount(1, $actualResult);
    }

    #[Test]
    public function it_returns_dismissibles_which_are_dismissed_until_past_date_time()
    {
        $dismisser = TestDismisserTypeOne::factory()->create();

        $now = CarbonImmutable::now();

        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeek(), $now->addWeek()))
            ->create();

        Dismissal::factory()
            ->for($dismisser, 'dismisser')
            ->for($dismissible)
            ->create([
                'dismissed_until' => $now->subDay(),
            ]);

        $actualResult = Dismissibles::getAllFor($dismisser);

        $this->assertInstanceOf(Collection::class, $actualResult);
        $this->assertCount(1, $actualResult);
    }

    #[Test]
    public function it_returns_dismissibles_which_are_dismissed_until_dismissible_active_from_date_time()
    {
        $dismisser = TestDismisserTypeOne::factory()->create();

        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeek(), $now->addWeek()))
            ->create();

        Dismissal::factory()
            ->for($dismisser, 'dismisser')
            ->for($dismissible)
            ->create([
                'dismissed_until' => $dismissible->active_from,
            ]);

        $actualResult = Dismissibles::getAllFor($dismisser);

        $this->assertInstanceOf(Collection::class, $actualResult);
        $this->assertCount(1, $actualResult);
    }

    #[Test]
    public function it_returns_dismissibles_ordered_by_active_from_ascending()
    {
        $dismisser = TestDismisserTypeOne::factory()->create();

        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $newestDismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subDay(), $now->addWeek()))
            ->create();

        /** @var Dismissible $dismissible */
        $oldestDismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subMonth(), $now->addWeek()))
            ->create();

        /** @var Dismissible $dismissible */
        $middleDismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeek(), $now->addWeek()))
            ->create();

        $actualResult = Dismissibles::getAllFor($dismisser);

        $this->assertTrue($actualResult->get(0)->is($oldestDismissible));
        $this->assertTrue($actualResult->get(1)->is($middleDismissible));
        $this->assertTrue($actualResult->get(2)->is($newestDismissible));
    }

    #[Test]
    public function it_does_not_return_dismissibles_which_are_dismissed_until_future_date_time()
    {
        $dismisser = TestDismisserTypeOne::factory()->create();

        $now = CarbonImmutable::now();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()
            ->active(CarbonPeriod::create($now->subWeek(), $now->addWeek()))
            ->create();

        Dismissal::factory()
            ->for($dismisser, 'dismisser')
            ->for($dismissible)
            ->create([
                'dismissed_until' => $now->addMinute(),
            ]);

        $actualResult = Dismissibles::getAllFor($dismisser);

        $this->assertInstanceOf(Collection::class, $actualResult);
        $this->assertCount(0, $actualResult);
    }
}
