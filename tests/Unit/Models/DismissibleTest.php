<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Tests\Unit\Models;

use DateTimeInterface;
use Illuminate\Support\Carbon;
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

    #[Test]
    public function with_global_scopes_it_does_not_return_the_dismissible_when_now_is_before_active_from()
    {
        Dismissible::factory()->create([
            'active_from' => Carbon::createFromFormat('d-m-Y H:i:s', '01-01-2023 01:00:00'),
        ]);

        Carbon::setTestNow(Carbon::createFromFormat('d-m-Y H:i:s', '01-01-2023 00:00:00'));

        $this->assertEmpty(Dismissible::all());
    }

    #[Test]
    public function without_global_scope_active_it_does_returns_the_dismissible_when_now_is_before_active_from()
    {
        Dismissible::factory()->create([
            'active_from' => Carbon::createFromFormat('d-m-Y H:i:s', '01-01-2023 01:00:00'),
        ]);

        Carbon::setTestNow(Carbon::createFromFormat('d-m-Y H:i:s', '01-01-2023 00:00:00'));

        $this->assertNotEmpty(Dismissible::withoutGlobalScope('active')->get());
    }

    #[Test]
    public function with_global_scopes_it_returns_the_dismissible_when_now_is_in_active_period()
    {
        Dismissible::factory()->create([
            'active_from'  => Carbon::createFromFormat('d-m-Y H:i:s', '01-01-2023 14:00:00'),
            'active_until' => Carbon::createFromFormat('d-m-Y H:i:s', '31-01-2023 23:59:59'),
        ]);

        Carbon::setTestNow(Carbon::createFromFormat('d-m-Y H:i:s', '15-01-2023 13:00:00'));

        $this->assertNotEmpty(Dismissible::all());
    }

    #[Test]
    public function without_global_scope_active_it_returns_the_dismissible_when_now_is_before_active_from()
    {
        Dismissible::factory()->create([
            'active_from'  => Carbon::createFromFormat('d-m-Y H:i:s', '01-01-2023 14:00:00'),
            'active_until' => Carbon::createFromFormat('d-m-Y H:i:s', '31-01-2023 23:59:59'),
        ]);

        Carbon::setTestNow(Carbon::createFromFormat('d-m-Y H:i:s', '15-01-2023 13:00:00'));

        $this->assertNotEmpty(Dismissible::withoutGlobalScope('active')->get());
    }

    #[Test]
    public function with_global_scopes_it_does_not_return_the_dismissible_when_now_is_after_active_period()
    {
        Dismissible::factory()->create([
            'active_from'  => Carbon::createFromFormat('d-m-Y H:i:s', '01-01-2023 14:00:00'),
            'active_until' => Carbon::createFromFormat('d-m-Y H:i:s', '31-01-2023 23:59:59'),
        ]);

        Carbon::setTestNow(Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2023 13:00:00'));

        $this->assertEmpty(Dismissible::all());
    }

    #[Test]
    public function with_global_scopes_it_does_returns_the_dismissible_when_now_is_after_active_from_and_active_until_is_null()
    {
        Dismissible::factory()->create([
            'active_from'  => Carbon::createFromFormat('d-m-Y H:i:s', '01-01-2023 14:00:00'),
            'active_until' => null,
        ]);

        Carbon::setTestNow(Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2023 13:00:00'));

        $this->assertNotEmpty(Dismissible::all());
    }

    #[Test]
    public function without_global_scope_active_it_returns_the_dismissible_when_now_is_after_active_period()
    {
        Dismissible::factory()->create([
            'active_from'  => Carbon::createFromFormat('d-m-Y H:i:s', '01-01-2023 14:00:00'),
            'active_until' => Carbon::createFromFormat('d-m-Y H:i:s', '31-01-2023 23:59:59'),
        ]);

        Carbon::setTestNow(Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2023 13:00:00'));

        $this->assertNotEmpty(Dismissible::withoutGlobalScope('active')->get());
    }
}
