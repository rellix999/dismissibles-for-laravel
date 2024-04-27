<?php

declare(strict_types=1);

namespace Rellix\LaravelDismissibles\Tests\Unit\Traits\HasDismissibles;

use PHPUnit\Framework\Attributes\Test;
use Rellix\LaravelDismissibles\Models\Dismissal;
use Rellix\LaravelDismissibles\Models\Dismisser;
use Rellix\LaravelDismissibles\Models\Dismissible;
use Rellix\LaravelDismissibles\Tests\BaseTestCase;

class DismissalsTest extends BaseTestCase
{
    public function it_returns_all_dismissals_by_a_dismisser_of_the_same_dismissibles()
    {
        /** @var Dismisser $dismisser */
        $dismisser = Dismisser::factory()->create();

        $dismissible = Dismissible::factory()->create();

        Dismissal::factory(5)
            ->for($dismisser)
            ->for($dismissible)
            ->create();

        $actualValue = $dismisser->dismissals;

        $this->assertCount(5, $actualValue);

        /** @var Dismissal $dismissal */
        foreach ($actualValue as $dismissal) {
            $this->assertTrue($dismissal->dismisser->is($dismisser));
            $this->assertTrue($dismissal->dismissible->is($dismissible));
        }
    }

    #[Test]
    public function it_returns_all_dismissals_by_a_dismisser_of_different_dismissibles()
    {
        /** @var Dismisser $dismisser */
        $dismisser = Dismisser::factory()->create();

        Dismissal::factory(5)
            ->for($dismisser)
            ->create();

        $actualValue = $dismisser->dismissals;

        $this->assertCount(5, $actualValue);

        /** @var Dismissal $dismissal */
        foreach ($actualValue as $dismissal) {
            $this->assertTrue($dismissal->dismisser->is($dismisser));
        }
    }

    #[Test]
    public function it_only_returns_dismissals_of_the_dismisser()
    {
        /** @var Dismisser $dismisser */
        $dismisser = Dismisser::factory()->create();

        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::factory()->create();

        $expectedDismissal = Dismissal::factory()
            ->for($dismisser)
            ->for($dismissible)
            ->create();

        Dismissal::factory()
            ->for($dismissible)
            ->create();

        Dismissal::factory()->create();

        $actualValue = $dismisser->dismissals;

        $this->assertCount(1, $actualValue);

        /** @var Dismissal $actualDismissal */
        $actualDismissal = $actualValue->first();

        $this->assertTrue($actualDismissal->is($expectedDismissal));
    }
}
