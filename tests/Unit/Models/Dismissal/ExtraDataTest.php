<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Tests\Unit\Models\Dismissal;

use PHPUnit\Framework\Attributes\Test;
use ThijsSchalk\LaravelDismissibles\Models\Dismissal;
use ThijsSchalk\LaravelDismissibles\Tests\BaseTestCase;

class ExtraDataTest extends BaseTestCase
{
    #[Test]
    public function it_returns_null_when_database_value_is_null()
    {
        $dismissal = Dismissal::factory()->create([
            'extra_data' => null,
        ]);

        $this->assertNull($dismissal->extra_data);
    }

    #[Test]
    public function it_returns_array_when_database_value_is_not_null()
    {
        $dismissal = Dismissal::factory()->create([
            'extra_data' => ['something'],
        ]);

        $this->assertIsArray($dismissal->extra_data);
    }
}
