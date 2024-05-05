<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Tests\Unit\Models\Dismissal;

use PHPUnit\Framework\Attributes\Test;
use Rellix\Dismissibles\Contracts\Dismisser;
use Rellix\Dismissibles\Models\Dismissal;
use Rellix\Dismissibles\Models\TestDismisserTypeOne;
use Rellix\Dismissibles\Models\TestDismisserTypeTwo;
use Rellix\Dismissibles\Tests\BaseTestCase;

class ScopeDismissedByTest extends BaseTestCase
{
    private Dismisser $dismisser;

    public function setUp(): void
    {
        parent::setUp();

        $this->dismisser = TestDismisserTypeOne::factory()->create();
    }

    #[Test]
    public function it_does_not_return_a_dismissal_when_not_dismissed()
    {
        $this->assertEmpty(Dismissal::dismissedBy($this->dismisser)->get());
    }

    #[Test]
    public function it_does_not_return_a_dismissal_when_dismissed_by_same_type_other_id()
    {
        Dismissal::factory()
            ->for(TestDismisserTypeOne::factory()->create(), 'dismisser')
            ->create();

        $this->assertEmpty(Dismissal::dismissedBy($this->dismisser)->get());
    }

    #[Test]
    public function it_does_not_return_a_dismissal_when_dismissed_by_same_id_other_type()
    {
        TestDismisserTypeOne::truncate();
        TestDismisserTypeTwo::truncate();

        $dismisserTypeOne = TestDismisserTypeOne::factory()->create();
        $dismisserTypeTwo = TestDismisserTypeTwo::factory()->create();

        $this->assertEquals($dismisserTypeOne->id, $dismisserTypeTwo->id);

        Dismissal::factory()
            ->for($dismisserTypeTwo, 'dismisser')
            ->create();

        $this->assertEmpty(Dismissal::dismissedBy($this->dismisser)->get());
    }

    #[Test]
    public function it_returns_a_dismissal_when_dismissed_by_same_type_and_id()
    {
        Dismissal::factory()
            ->for($this->dismisser, 'dismisser')
            ->create();

        $this->assertNotEmpty(Dismissal::dismissedBy($this->dismisser)->get());
    }

    #[Test]
    public function it_returns_the_correct_dismissal_when_dismissed_by_same_type_and_id()
    {
        $dismissal = Dismissal::factory()
            ->for($this->dismisser, 'dismisser')
            ->create();

        $actualDismissals = Dismissal::dismissedBy($this->dismisser)->get();

        $this->assertCount(1, $actualDismissals);
        $this->assertTrue($dismissal->is($actualDismissals->first()));
    }
}
