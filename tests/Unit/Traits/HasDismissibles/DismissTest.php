<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Tests\Unit\Traits\HasDismissibles;

use PHPUnit\Framework\Attributes\Test;
use ThijsSchalk\LaravelDismissibles\Concerns\Dismiss;
use ThijsSchalk\LaravelDismissibles\Models\Dismisser;
use ThijsSchalk\LaravelDismissibles\Models\Dismissible;
use ThijsSchalk\LaravelDismissibles\Tests\BaseTestCase;

class DismissTest extends BaseTestCase
{
    private readonly Dismissible $dismissible;
    private readonly Dismisser $dismisser;

    public function setUp(): void
    {
        parent::setUp();

        $this->dismissible = Dismissible::factory()->create();
        $this->dismisser = Dismisser::factory()->create();
    }

    #[Test]
    public function it_returns_an_object()
    {
        $actual = $this->dismisser->dismiss($this->dismissible);

        $this->assertIsObject($actual);
    }

    #[Test]
    public function it_returns_a_dismiss_object()
    {
        $actual = $this->dismisser->dismiss($this->dismissible);

        $this->assertInstanceOf(Dismiss::class, $actual);
    }

    #[Test]
    public function the_dismiss_object_has_the_correct_dismisser()
    {
        $actual = $this->dismisser->dismiss($this->dismissible);

        $this->assertEquals($actual->dismisser, $this->dismisser);
    }

    #[Test]
    public function the_dismiss_object_has_the_correct_dismissible()
    {
        $actual = $this->dismisser->dismiss($this->dismissible);

        $this->assertEquals($actual->dismissible, $this->dismissible);
    }
}
