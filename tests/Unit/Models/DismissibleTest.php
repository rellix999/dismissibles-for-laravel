<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Tests\Unit\Models;

use DateTimeInterface;
use PHPUnit\Framework\Attributes\Test;
use ThijsSchalk\LaravelDismissibles\Models\Dismissible;
use ThijsSchalk\LaravelDismissibles\Tests\BaseTestCase;

class DismissibleTest extends BaseTestCase
{
    #[Test]
    public function it_sets_the_uuid_on_creation()
    {
        $dismissible = Dismissible::factory()->create();

        $this->assertNotEmpty($dismissible->uuid);
    }

    #[Test]
    public function uuid_getter_returns_a_string()
    {
        $dismissible = Dismissible::factory()->create();

        $this->assertIsString($dismissible->uuid);
    }

    #[Test]
    public function active_from_getter_returns_a_date_time_object()
    {
        $dismissible = Dismissible::factory()->create();

        $this->assertTrue($dismissible->active_from instanceof DateTimeInterface);
    }

    #[Test]
    public function active_until_getter_returns_a_date_time_object()
    {
        $dismissible = Dismissible::factory()->create();

        $this->assertTrue($dismissible->active_from instanceof DateTimeInterface);
    }
}
