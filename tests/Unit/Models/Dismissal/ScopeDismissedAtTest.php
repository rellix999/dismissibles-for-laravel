<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Tests\Unit\Models\Dismissal;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Rellix\Dismissibles\Models\Dismissal;
use Rellix\Dismissibles\Tests\BaseTestCase;

class ScopeDismissedAtTest extends BaseTestCase
{
    #[Test]
    public function it_does_not_return_a_dismissal_when_none_exist()
    {
        $this->assertEmpty(Dismissal::dismissedAt()->get());
    }

    #[Test]
    public function it_does_not_return_a_dismissal_when_dismissed_until_past_date_time()
    {
        Dismissal::factory()->create([
            'dismissed_until' => Carbon::yesterday(),
        ]);

        $this->assertEmpty(Dismissal::dismissedAt()->get());
    }

    #[Test]
    public function it_does_not_return_a_dismissal_when_dismissed_until_equals_now()
    {
        $now = Carbon::now();

        Carbon::setTestNow($now);

        Dismissal::factory()->create([
            'dismissed_until' => $now,
        ]);

        $this->assertEmpty(Dismissal::dismissedAt()->get());
    }

    #[Test]
    public function it_returns_a_dismissal_when_dismissed_until_future_date_time()
    {
        Dismissal::factory()->create([
            'dismissed_until' => Carbon::tomorrow(),
        ]);

        $this->assertNotEmpty(Dismissal::dismissedAt()->get());
    }

    #[Test]
    public function it_returns_a_dismissal_when_dismissed_until_forever()
    {
        Dismissal::factory()->create([
            'dismissed_until' => null,
        ]);

        $this->assertNotEmpty(Dismissal::dismissedAt()->get());
    }
}
