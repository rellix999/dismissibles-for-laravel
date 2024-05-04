<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Tests\Unit\Concerns;

use DateTimeInterface;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Rellix\Dismissibles\Concerns\Dismiss;
use Rellix\Dismissibles\Contracts\Dismisser;
use Rellix\Dismissibles\Models\Dismissal;
use Rellix\Dismissibles\Models\Dismissible;
use Rellix\Dismissibles\Models\TestDismisserTypeOne;
use Rellix\Dismissibles\Models\TestDismisserTypeTwo;
use Rellix\Dismissibles\Tests\BaseTestCase;

class DismissTest extends BaseTestCase
{
    private readonly Dismissible $dismissible;
    private readonly Dismisser $dismisser;

    private readonly Dismiss $dismiss;

    public function setUp(): void
    {
        parent::setUp();

        $this->dismissible = Dismissible::factory()->create();
        $this->dismisser = TestDismisserTypeOne::factory()->create();

        $this->dismiss = new Dismiss($this->dismisser, $this->dismissible);
    }

    #[Test]
    public function it_creates_a_dismissal_with_the_correct_type_and_id_for_type_one()
    {
        $dismissible = Dismissible::factory()->create();
        $dismisser = TestDismisserTypeOne::factory()->create();
        $dismiss = new Dismiss($dismisser, $dismissible);

        $dismiss->untilTomorrow();

        /** @var Dismissal $dismissal */
        $dismissal = Dismissal::orderBy('id', 'desc')->first();

        $this->assertDatabaseHas('dismissals', [
            'id'             => $dismissal->id,
            'dismisser_type' => TestDismisserTypeOne::class,
            'dismisser_id'   => $dismisser->id,
        ]);
    }

    #[Test]
    public function it_creates_a_dismissal_with_the_correct_type_and_id_for_type_two()
    {
        $dismissible = Dismissible::factory()->create();
        $dismisser = TestDismisserTypeTwo::factory()->create();
        $dismiss = new Dismiss($dismisser, $dismissible);

        $dismiss->untilTomorrow();

        /** @var Dismissal $dismissal */
        $dismissal = Dismissal::orderBy('id', 'desc')->first();

        $this->assertDatabaseHas('dismissals', [
            'id'             => $dismissal->id,
            'dismisser_type' => TestDismisserTypeTwo::class,
            'dismisser_id'   => $dismisser->id,
        ]);
    }

    #[Test]
    #[DataProvider('untilTomorrowDataProvider')]
    public function until_tomorrow(string $now, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->untilTomorrow();

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => $expectedDismissedUntil,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    #[Test]
    #[DataProvider('untilNextWeekDataProvider')]
    public function until_next_week(string $now, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->untilNextWeek();

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => $expectedDismissedUntil,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    #[Test]
    #[DataProvider('untilNextMonthDataProvider')]
    public function until_next_month(string $now, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->untilNextMonth();

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => $expectedDismissedUntil,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    #[Test]
    #[DataProvider('untilNextQuarterDataProvider')]
    public function until_next_quarter(string $now, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->untilNextQuarter();

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => $expectedDismissedUntil,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    #[Test]
    #[DataProvider('untilNextYearDataProvider')]
    public function until_next_year(string $now, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->untilNextYear();

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => $expectedDismissedUntil,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    #[Test]
    #[DataProvider('untilDataProvider')]
    public function until(string $now, DateTimeInterface $dateTime, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->until($dateTime);

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => $expectedDismissedUntil,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    #[Test]
    #[DataProvider('forHoursDataProvider')]
    public function for_hours(string $now, int $hours, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->forHours($hours);

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => $expectedDismissedUntil,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    #[Test]
    #[DataProvider('forDaysDataProvider')]
    public function for_days(string $now, int $days, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->forDays($days);

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => $expectedDismissedUntil,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    #[Test]
    #[DataProvider('forWeeksDataProvider')]
    public function for_weeks(string $now, int $weeks, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->forWeeks($weeks);

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => $expectedDismissedUntil,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    #[Test]
    #[DataProvider('forMonthsDataProvider')]
    public function for_months(string $now, int $months, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->forMonths($months);

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => $expectedDismissedUntil,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    #[Test]
    #[DataProvider('forYearsDataProvider')]
    public function for_years(string $now, int $years, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->forYears($years);

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => $expectedDismissedUntil,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    #[Test]
    #[DataProvider('foreverDataProvider')]
    public function forever(string $now)
    {
        $this->setTestNow($now);

        $this->dismiss->forever();

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => null,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    public static function untilTomorrowDataProvider(): array
    {
        return [
            ['2023-01-01 00:00:00', '2023-01-02 00:00:00'],
            ['2023-08-16 14:00:00', '2023-08-17 00:00:00'],
            ['2023-12-31 23:59:59', '2024-01-01 00:00:00'],
        ];
    }

    public static function untilNextWeekDataProvider()
    {
        return [
            ['2023-08-16 14:00:00', '2023-08-21 00:00:00'],
            ['2023-09-01 12:00:00', '2023-09-04 00:00:00'],
        ];
    }

    public static function untilNextMonthDataProvider()
    {
        return [
            ['2023-08-16 14:00:00', '2023-09-01 00:00:00'],
            ['2023-09-01 13:01:59', '2023-10-01 00:00:00'],
        ];
    }

    public static function untilNextQuarterDataProvider()
    {
        return [
            ['2023-08-16 14:00:00', '2023-10-01 00:00:00'],
            ['2023-01-01 22:10:13', '2023-04-01 00:00:00'],
        ];
    }

    public static function untilNextYearDataProvider()
    {
        return [
            ['2023-08-16 14:00:00', '2024-01-01 00:00:00'],
            ['2023-01-01 14:00:00', '2024-01-01 00:00:00'],
            ['2024-12-31 14:00:00', '2025-01-01 00:00:00'],
        ];
    }

    public static function untilDataProvider()
    {
        return [
            [
                '2023-08-16 14:00:00',
                Carbon::createFromFormat('d-m-Y H:i:s', '20-08-2023 12:32:02'),
                '2023-08-20 12:32:02',
            ],
            [
                '2023-01-01 14:00:00',
                Carbon::createFromFormat('d-m-Y H:i:s', '01-01-2023 14:00:01'),
                '2023-01-01 14:00:01',
            ],
        ];
    }

    public static function forHoursDataProvider(): array
    {
        return [
            ['2023-08-16 14:00:00', 1, '2023-08-16 15:00:00'],
            ['2023-08-16 14:00:00', 2, '2023-08-16 16:00:00'],
            ['2023-08-16 14:00:00', 10, '2023-08-17 00:00:00'],
            ['2023-08-16 14:00:00', 24, '2023-08-17 14:00:00'],
            ['2023-08-16 14:00:00', 48, '2023-08-18 14:00:00'],
            ['2023-08-16 14:00:00', 100, '2023-08-20 18:00:00'],
        ];
    }

    public static function forDaysDataProvider(): array
    {
        return [
            ['2023-08-16 14:00:00', 1, '2023-08-17 14:00:00'],
            ['2023-08-16 14:00:00', 2, '2023-08-18 14:00:00'],
            ['2023-08-16 14:00:00', 7, '2023-08-23 14:00:00'],
            ['2023-08-16 14:00:00', 100, '2023-11-24 14:00:00'],
            ['2023-08-16 14:00:00', 365, '2024-08-15 14:00:00'],
        ];
    }

    public static function forWeeksDataProvider(): array
    {
        return [
            ['2023-08-16 14:00:00', 1, '2023-08-23 14:00:00'],
            ['2023-08-16 14:00:00', 2, '2023-08-30 14:00:00'],
            ['2023-08-16 14:00:00', 7, '2023-10-04 14:00:00'],
            ['2023-08-16 14:00:00', 52, '2024-08-14 14:00:00'],
        ];
    }

    public static function forMonthsDataProvider(): array
    {
        return [
            ['2023-08-16 14:00:00', 1, '2023-09-16 14:00:00'],
            ['2023-08-16 14:00:00', 2, '2023-10-16 14:00:00'],
            ['2023-08-16 14:00:00', 6, '2024-02-16 14:00:00'],
            ['2023-08-16 14:00:00', 12, '2024-08-16 14:00:00'],
        ];
    }

    public static function forYearsDataProvider(): array
    {
        return [
            ['2023-08-16 14:00:00', 1, '2024-08-16 14:00:00'],
            ['2023-08-16 14:00:00', 2, '2025-08-16 14:00:00'],
            ['2023-08-16 14:00:00', 6, '2029-08-16 14:00:00'],
            ['2023-08-16 14:00:00', 12, '2035-08-16 14:00:00'],
            ['2023-08-16 14:00:00', 50, '2073-08-16 14:00:00'],
        ];
    }

    public static function foreverDataProvider()
    {
        return [
            ['2023-08-16 14:00:00'],
            ['2023-08-16 14:00:00'],
            ['2023-08-16 14:00:00'],
        ];
    }

    private function setTestNow(string $now)
    {
        Carbon::setTestNow(Carbon::createFromFormat('Y-m-d H:i:s', $now));
    }

    private function getExpectedDismissalData(array $expectedData): array
    {
        return [
            'dismissible_id' => $this->dismissible->id,
            'dismisser_id'   => $this->dismisser->id,
            'dismisser_type' => TestDismisserTypeOne::class,
            ...$expectedData,
        ];
    }
}
