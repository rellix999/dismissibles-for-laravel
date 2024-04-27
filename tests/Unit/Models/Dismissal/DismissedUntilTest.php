<?php

declare(strict_types=1);

namespace Rellix\LaravelDismissibles\Tests\Unit\Models\Dismissal;

use DateTimeInterface;
use Illuminate\Support\Facades\Date;
use PHPUnit\Framework\Attributes\Test;
use Rellix\LaravelDismissibles\Models\Dismissal;
use Rellix\LaravelDismissibles\Tests\BaseTestCase;

class DismissedUntilTest extends BaseTestCase
{
    #[Test]
    public function it_returns_a_date_time_object()
    {
        $dismissal = Dismissal::factory()->create([
            'dismissed_until' => Date::now(),
        ]);

        $this->assertTrue($dismissal->dismissed_until instanceof DateTimeInterface);
    }
}
