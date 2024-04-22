<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Tests\Unit\Models;

use DateTimeInterface;
use PHPUnit\Framework\Attributes\Test;
use ThijsSchalk\LaravelDismissibles\Models\Dismissal;
use ThijsSchalk\LaravelDismissibles\Tests\BaseTestCase;

class DismissalTest extends BaseTestCase
{
    #[Test]
    public function dismissed_until_getter_returns_date_time_object()
    {
        $dismissal = Dismissal::factory()->create();

        $this->assertTrue($dismissal->dismissed_until instanceof DateTimeInterface);
    }

    #[Test]
    public function extra_data_getter_returns_null_when_database_value_is_null()
    {
        $dismissal = Dismissal::factory()->create([
            'extra_data' => null,
        ]);

        $this->assertNull($dismissal->extra_data);
    }

    #[Test]
    public function extra_data_getter_returns_array_when_database_value_is_not_null()
    {
        $dismissal = Dismissal::factory()->create([
            'extra_data' => ['something'],
        ]);

        $this->assertIsArray($dismissal->extra_data);
    }
}
