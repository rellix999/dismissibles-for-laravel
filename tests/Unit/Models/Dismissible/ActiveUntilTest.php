<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Tests\Unit\Models\Dismissible;

use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use ThijsSchalk\LaravelDismissibles\Models\Dismissible;
use ThijsSchalk\LaravelDismissibles\Tests\BaseTestCase;

class ActiveUntilTest extends BaseTestCase
{
    #[Test]
    public function active_until_getter_returns_a_carbon_immutable_object()
    {
        $dismissible = Dismissible::factory()
            ->create([
                'active_until' => Carbon::now()->addDay(),
            ]);

        $this->assertTrue($dismissible->active_until instanceof CarbonImmutable);
    }
}
