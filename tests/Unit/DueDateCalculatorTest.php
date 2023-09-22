<?php

declare(strict_types=1);

namespace Zolli\Emarsys\Homework\Tests\Unit;

use \DateTimeImmutable;
use \ReflectionObject;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Zolli\Emarsys\Homework\DueDateCalculator;
use Zolli\Emarsys\Homework\Model\WorkTerms;

class DueDateCalculatorTest extends TestCase
{
    private DueDateCalculator $subject;

    protected function setUp(): void
    {
        $this->subject = new DueDateCalculator($this->getWorkTermMock());
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

    #[DataProvider('workHourCheckDataProvider')]
    public function testItChecksDateTimeForInWorkingHoursCorrectly(DateTimeImmutable $input, bool $expected): void
    {
        $objectReflector = new ReflectionObject($this->subject);
        $methodReflector = $objectReflector->getMethod('isTimeInsideWorkingHours');
        $methodReflector->setAccessible(true);

        $this->assertEquals($expected, $methodReflector->invoke($this->subject, $input));

    }

    #[DataProvider('workDayCheckDataProvider')]
    public function testItChecksDateToBeInWorkingDays(DateTimeImmutable $input, bool $expected): void
    {
        $objectReflector = new ReflectionObject($this->subject);
        $methodReflector = $objectReflector->getMethod('isDateAWorkday');
        $methodReflector->setAccessible(true);

        $this->assertEquals($expected, $methodReflector->invoke($this->subject, $input));

    }

    private function getWorkTermMock(): MockObject|WorkTerms
    {
        $workTermMock = $this->getMockBuilder(WorkTerms::class)->disableOriginalConstructor()->getMock();

        $workTermMock->expects($this->atLeastOnce())
            ->method('getWorkingDays')
            ->willReturn(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);

        $workTermMock->expects($this->atLeastOnce())
            ->method('getWorkdayStartHour')
            ->willReturn(9);

        $workTermMock->expects($this->atLeastOnce())
            ->method('getWorkdayEndHour')
            ->willReturn(17);

        return $workTermMock;
    }

    public static function workHourCheckDataProvider(): array
    {
        return [
            'midday' => [new DateTimeImmutable('2023-09-22 12:00:00'), true],
            'workday_start' => [new DateTimeImmutable('2023-09-22 09:00:00'), true],
            'before_start' => [new DateTimeImmutable('2023-09-22 08:59:59'), false],
            'after_end' => [new DateTimeImmutable('2023-09-22 21:32:58'), false],
        ];
    }

    public static function workDayCheckDataProvider(): array
    {
        return [
            'friday' => [new DateTimeImmutable('2023-09-22 12:00:00'), true],
            'saturday' => [new DateTimeImmutable('2023-09-23 09:00:00'), false],
        ];
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
