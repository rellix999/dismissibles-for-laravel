<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Tests\Unit\Models\Dismissible;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\Test;
use Rellix\Dismissibles\Models\Dismissible;
use Rellix\Dismissibles\Tests\BaseTestCase;

class ActiveFromTest extends BaseTestCase
{
    #[Test]
    public function it_returns_a_carbon_immutable_object()
    {
        $dismissible = Dismissible::factory()->create();

        $this->assertTrue($dismissible->active_from instanceof CarbonImmutable);
    }
}
