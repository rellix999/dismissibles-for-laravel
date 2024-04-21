<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissible\Tests\Unit;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use ThijsSchalk\LaravelDismissible\Models\Dismissible;
use ThijsSchalk\LaravelDismissible\Tests\BaseTestCase;
use ThijsSchalk\LaravelDismissible\Tests\Models\Dismisser;

class DismissTest extends BaseTestCase
{
    #[Test]
    public function it_works()
    {
        $dismissible = Dismissible::create([
            'name'         => 'Popup 1',
            'active_from'  => Carbon::yesterday(),
            'active_until' => Carbon::now()->addWeek(),
        ]);

        $this->assertNotNull($dismissible);
        $this->assertEquals(1, Dismissible::count());
    }

    #[Test]
    public function it_can_create_a_dismisser()
    {
        Dismisser::create();

        $this->assertDatabaseCount('dismissers', 1);
    }
}
