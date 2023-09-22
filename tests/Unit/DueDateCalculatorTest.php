<?php

declare(strict_types=1);

namespace Zolli\Emarsys\Homework\Tests\Unit;

use \DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zolli\Emarsys\Homework\DueDateCalculator;

class DueDateCalculatorTest extends TestCase
{
    private DueDateCalculator $subject;

    protected function setUp(): void
    {
        $this->subject = new DueDateCalculator();
    }

    #[DataProvider('dataProviderValidCases')]
    public function testItCalculatesTheCorrectDueDate(
        DateTimeImmutable $startDate,
        int $turnaroundTime,
        DateTimeImmutable $expectedResult): void
    {
        $result = $this->subject->calculateDueDate($startDate, $turnaroundTime);

        $this->assertEquals($result->format('Y-m-d H:i:s'), $expectedResult->format('Y-m-d H:i:s'));
    }

    public static function dataProviderValidCases(): array
    {
        return [
            'same_day_resolution' => [
                new DateTimeImmutable('2023-09-22 09:48:52'),
                7,
                new DateTimeImmutable('2023-09-22 16:48:52')
            ],
            'cannot_finish_in_the_week' => [
                new DateTimeImmutable('2023-09-22 09:05:38'),
                8,
                new DateTimeImmutable('2023-09-25 09:05:38')
            ],
            'simple_one_hour_later' => [
                new DateTimeImmutable('2023-09-25 09:00:00'),
                9,
                new DateTimeImmutable('2023-09-26 10:00:00')
            ],
            'long_turnaround_time_7d' => [
                new DateTimeImmutable('2023-09-22 09:08:18'),
                56,
                new DateTimeImmutable('2023-10-03 09:08:18')
            ],
        ];
    }
}
