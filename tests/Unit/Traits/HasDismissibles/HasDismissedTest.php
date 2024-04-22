<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Tests\Unit\Traits\HasDismissibles;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use ThijsSchalk\LaravelDismissibles\Models\Dismissal;
use ThijsSchalk\LaravelDismissibles\Models\Dismisser;
use ThijsSchalk\LaravelDismissibles\Models\Dismissible;
use ThijsSchalk\LaravelDismissibles\Tests\BaseTestCase;

class HasDismissedTest extends BaseTestCase
{
    private Dismisser $dismisser;
    private Dismissible $dismissible;

    public function setUp(): void
    {
        parent::setUp();

        $this->dismissible = Dismissible::factory()->create();
        $this->dismisser = Dismisser::factory()->create();
    }

    #[Test]
    public function it_returns_false_when_there_are_no_dismissals()
    {
        $actualValue = $this->dismisser->hasDismissed($this->dismissible);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_when_user_has_dismissals_but_not_for_this_dismissible()
    {
        Dismissal::factory()
            ->for($this->dismisser)
            ->create();

        $actualValue = $this->dismisser->hasDismissed($this->dismissible);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_true_when_dismissed_until_future_date()
    {
        Dismissal::factory()
            ->for($this->dismisser)
            ->for($this->dismissible)
            ->create([
                'dismissed_until' => Carbon::createFromFormat('d-m-Y H:i:s', '05-01-2023 00:00:01'),
            ]);

        Carbon::setTestNow(Carbon::createFromFormat('d-m-Y H:i:s', '05-01-2023 00:00:00'));

        $actualValue = $this->dismisser->hasDismissed($this->dismissible);

        $this->assertTrue($actualValue);
    }

    #[Test]
    public function it_returns_true_when_dismissed_until_is_null()
    {
        Dismissal::factory()
            ->for($this->dismisser)
            ->for($this->dismissible)
            ->create([
                'dismissed_until' => null,
            ]);

        $actualValue = $this->dismisser->hasDismissed($this->dismissible);

        $this->assertTrue($actualValue);
    }

    #[Test]
    public function it_returns_false_when_dismissed_until_now()
    {
        Dismissal::factory()
            ->for($this->dismisser)
            ->for($this->dismissible)
            ->create([
                'dismissed_until' => Carbon::createFromFormat('d-m-Y H:i:s', '05-01-2023 00:00:00'),
            ]);

        Carbon::setTestNow(Carbon::createFromFormat('d-m-Y H:i:s', '05-01-2023 00:00:00'));

        $actualValue = $this->dismisser->hasDismissed($this->dismissible);

        $this->assertFalse($actualValue);
    }

    #[Test]
    public function it_returns_false_when_dismissed_until_past_date()
    {
        Dismissal::factory()
            ->for($this->dismisser)
            ->for($this->dismissible)
            ->create([
                'dismissed_until' => Carbon::createFromFormat('d-m-Y H:i:s', '05-01-2023 00:00:00'),
            ]);

        Carbon::setTestNow(Carbon::createFromFormat('d-m-Y H:i:s', '05-01-2023 00:00:01'));

        $actualValue = $this->dismisser->hasDismissed($this->dismissible);

        $this->assertFalse($actualValue);
    }
}
