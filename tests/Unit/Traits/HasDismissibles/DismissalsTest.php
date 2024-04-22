<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Tests\Unit\Traits\HasDismissibles;

use PHPUnit\Framework\Attributes\Test;
use ThijsSchalk\LaravelDismissibles\Models\Dismissal;
use ThijsSchalk\LaravelDismissibles\Models\Dismisser;
use ThijsSchalk\LaravelDismissibles\Models\Dismissible;
use ThijsSchalk\LaravelDismissibles\Tests\BaseTestCase;

class DismissalsTest extends BaseTestCase
{
    private readonly Dismissible $dismissible;
    private readonly Dismisser $dismisser;

    public function setUp(): void
    {
        parent::setUp();

        $this->dismissible = Dismissible::factory()->create();
        $this->dismisser = Dismisser::factory()->create();
    }

    #[Test]
    public function it_returns_all_dismissals_of_dismisser()
    {
        Dismissal::factory(5)
            ->for($this->dismisser)
            ->for($this->dismissible)
            ->create();

        $actualValue = $this->dismisser->dismissals;

        $this->assertCount(5, $actualValue);

        /** @var Dismissal $dismissal */
        foreach ($actualValue as $dismissal) {
            $this->assertTrue($dismissal->dismisser->is($this->dismisser));
            $this->assertTrue($dismissal->dismissible->is($this->dismissible));
        }
    }

    #[Test]
    public function it_does_not_return_dismissals_of_other_dismissers()
    {
        /** @var Dismissal $expectedDismissal */
        $expectedDismissal = Dismissal::factory()
            ->for($this->dismisser)
            ->for($this->dismissible)
            ->create();

        /** @var Dismissal $notExpectedDismissal */
        $notExpectedDismissal = Dismissal::factory()
            ->for(Dismisser::factory()->create())
            ->for($this->dismissible)
            ->create();

        $actualValue = $this->dismisser->dismissals;

        $this->assertCount(1, $actualValue);

        /** @var Dismissal $actualDismissal */
        $actualDismissal = $actualValue->first();

        $this->assertTrue($actualDismissal->is($expectedDismissal));
        $this->assertFalse($actualDismissal->is($notExpectedDismissal));
    }
}
