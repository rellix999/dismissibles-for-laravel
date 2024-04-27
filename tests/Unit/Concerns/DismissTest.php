<?php

declare(strict_types=1);

namespace Rellix\LaravelDismissibles\Tests\Unit\Concerns;

use DateTimeInterface;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Rellix\LaravelDismissibles\Concerns\Dismiss;
use Rellix\LaravelDismissibles\Models\Dismisser;
use Rellix\LaravelDismissibles\Models\Dismissible;
use Rellix\LaravelDismissibles\Tests\BaseTestCase;

class DismissTest extends BaseTestCase
{
    private readonly Dismissible $dismissible;
    private readonly Dismisser $dismisser;

    private readonly Dismiss $dismiss;

    public function setUp(): void
    {
        parent::setUp();

        $this->dismissible = Dismissible::factory()->create();
        $this->dismisser = Dismisser::factory()->create();

        $this->dismiss = new Dismiss($this->dismisser, $this->dismissible);
    }

    #[Test]
    #[DataProvider('forTodayDataProvider')]
    public function for_today(string $now, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->forToday();

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
    #[DataProvider('forThisCalendarWeekDataProvider')]
    public function for_this_calendar_week(string $now, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->forThisCalendarWeek();

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => $expectedDismissedUntil,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    #[Test]
    #[DataProvider('forThisCalendarMonthDataProvider')]
    public function for_this_calendar_month(string $now, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->forThisCalendarMonth();

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => $expectedDismissedUntil,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    #[Test]
    #[DataProvider('forThisCalendarQuarterDataProvider')]
    public function for_this_calendar_quarter(string $now, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->forThisCalendarQuarter();

        $expectedData = $this->getExpectedDismissalData([
            'dismissed_until' => $expectedDismissedUntil,
        ]);

        $this->assertDatabaseHas('dismissals', $expectedData);
    }

    #[Test]
    #[DataProvider('forThisCalendarYearDataProvider')]
    public function for_this_calendar_year(string $now, string $expectedDismissedUntil)
    {
        $this->setTestNow($now);

        $this->dismiss->forThisCalendarYear();

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

    public static function forTodayDataProvider(): array
    {
        return [
            ['2023-01-01 00:00:00', '2023-01-01 23:59:59'],
            ['2023-08-16 14:00:00', '2023-08-16 23:59:59'],
            ['2023-12-31 23:59:59', '2023-12-31 23:59:59'],
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

    public static function forThisCalendarWeekDataProvider()
    {
        return [
            ['2023-08-16 14:00:00', '2023-08-20 23:59:59'],
            ['2023-09-01 12:00:00', '2023-09-03 23:59:59'],
        ];
    }

    public static function forThisCalendarMonthDataProvider()
    {
        return [
            ['2023-08-16 14:00:00', '2023-08-31 23:59:59'],
            ['2023-09-01 13:01:59', '2023-09-30 23:59:59'],
        ];
    }

    public static function forThisCalendarQuarterDataProvider()
    {
        return [
            ['2023-08-16 14:00:00', '2023-09-30 23:59:59'],
            ['2023-01-01 22:10:13', '2023-03-31 23:59:59'],
        ];
    }

    public static function forThisCalendarYearDataProvider()
    {
        return [
            ['2023-08-16 14:00:00', '2023-12-31 23:59:59'],
            ['2023-01-01 14:00:00', '2023-12-31 23:59:59'],
            ['2024-12-31 14:00:00', '2024-12-31 23:59:59'],
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
            'dismisser_type' => Dismisser::class,
            ...$expectedData,
        ];
    }
}
