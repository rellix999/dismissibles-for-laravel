<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Tests\Unit\Models\Dismissible;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\Test;
use ThijsSchalk\LaravelDismissibles\Models\Dismissible;
use ThijsSchalk\LaravelDismissibles\Tests\BaseTestCase;

class ActiveFromTest extends BaseTestCase
{
    #[Test]
    public function it_returns_a_carbon_immutable_object()
    {
        $dismissible = Dismissible::factory()->create();

        $this->assertTrue($dismissible->active_from instanceof CarbonImmutable);
    }
}
