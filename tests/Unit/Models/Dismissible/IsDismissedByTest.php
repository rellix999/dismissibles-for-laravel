<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Tests\Unit\Models\Dismissible;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Rellix\Dismissibles\Contracts\Dismisser;
use Rellix\Dismissibles\Models\Dismissal;
use Rellix\Dismissibles\Models\Dismissible;
use Rellix\Dismissibles\Models\TestDismisserOne;
use Rellix\Dismissibles\Models\TestDismisserTwo;
use Rellix\Dismissibles\Tests\BaseTestCase;

class IsDismissedByTest extends BaseTestCase
{
    private Dismisser $dismisser;

    public function setUp(): void
    {
        parent::setUp();

        $this->dismisser = TestDismisserOne::factory()->create();
    }

    #[Test]
    public function it_returns_false_when_dismisser_has_not_dismissed()
    {
        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()->create();

        $actualValue = $dismissible->isDismissedBy($this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_when_dismisser_has_dismissed_different_dismissible_until_past_date()
    {
        Dismissal::factory()
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => Carbon::yesterday(),
            ]);

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()->create();

        $actualValue = $dismissible->isDismissedBy($this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_when_dismisser_has_dismissed_different_dismissible_until_future_date()
    {
        Dismissal::factory()
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => Carbon::tomorrow(),
            ]);

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()->create();

        $actualValue = $dismissible->isDismissedBy($this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_when_dismisser_has_dismissed_until_past_date()
    {
        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => Carbon::yesterday(),
            ]);

        $actualValue = $dismissible->isDismissedBy($this->dismisser);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_when_dismisser_has_dismissed_until_future_date()
    {
        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($this->dismisser, 'dismisser')
            ->create([
                'dismissed_until' => Carbon::tomorrow(),
            ]);

        $actualValue = $dismissible->isDismissedBy($this->dismisser);

        $this->assertTrue($actualValue);
    }

    #[Test]
    public function it_returns_the_correct_value_when_dismissal_with_same_id_but_different_type_exists()
    {
        TestDismisserOne::truncate();
        TestDismisserTwo::truncate();

        $dismisserOfTypeOne = TestDismisserOne::factory()->create();
        $dismisserOfTypeTwo = TestDismisserTwo::factory()->create();

        $this->assertEquals($dismisserOfTypeOne->id, $dismisserOfTypeTwo->id);

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()->active()->create();

        Dismissal::factory()
            ->for($dismissible)
            ->for($dismisserOfTypeOne, 'dismisser')
            ->create([
                'dismissed_until' => Carbon::tomorrow(),
            ]);

        $this->assertTrue($dismissible->isDismissedBy($dismisserOfTypeOne));
        $this->assertFalse($dismissible->isDismissedBy($dismisserOfTypeTwo));
    }
}
