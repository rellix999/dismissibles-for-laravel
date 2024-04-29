<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Tests\Unit\Facades\Dismissibles;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Rellix\Dismissibles\Facades\Dismissibles;
use Rellix\Dismissibles\Models\Dismissal;
use Rellix\Dismissibles\Models\Dismisser;
use Rellix\Dismissibles\Models\Dismissible;
use Rellix\Dismissibles\Tests\BaseTestCase;

class IsDismissedTest extends BaseTestCase
{
    private readonly Dismisser $dismisser;

    public function setUp(): void
    {
        parent::setUp();

        $this->dismisser = Dismisser::factory()->create();
    }

    #[Test]
    public function it_returns_false_when_there_are_no_dismissals()
    {
        $dismissible = Dismissible::factory()->create();

        $actualValue = Dismissibles::isDismissed($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_when_user_has_dismissals_but_not_for_this_dismissible()
    {
        $dismissible = Dismissible::factory()->create();

        Dismissal::factory()
            ->for($this->dismisser)
            ->create();

        $actualValue = Dismissibles::isDismissed($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_when_user_has_not_dismissed()
    {
        $dismissible = Dismissible::factory()->create();

        $actualValue = Dismissibles::isDismissed($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_when_user_has_dismissed_until_past_date()
    {
        $dismissible = Dismissible::factory()->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser)
            ->create([
                'dismissed_until' => Carbon::now()->subDay(),
            ]);

        $actualValue = Dismissibles::isDismissed($dismissible->name, $this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_true_when_user_has_dismissed_until_future_date()
    {
        $dismissible = Dismissible::factory()->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser)
            ->create([
                'dismissed_until' => Carbon::now()->addDay(),
            ]);

        $actualValue = Dismissibles::isDismissed($dismissible->name, $this->dismisser);

        $this->assertTrue($actualValue);
    }

    #[Test]
    public function it_returns_true_when_user_has_dismissed_until_null()
    {
        $dismissible = Dismissible::factory()->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser)
            ->create([
                'dismissed_until' => null,
            ]);

        $actualValue = Dismissibles::isDismissed($dismissible->name, $this->dismisser);

        $this->assertTrue($actualValue);
    }
}
