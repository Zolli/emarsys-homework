<?php

declare(strict_types=1);

namespace Zolli\Emarsys\Homework\Tests\Unit\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zolli\Emarsys\Homework\Exception\WorkTermsException;
use Zolli\Emarsys\Homework\Model\WorkTerms;

class WorkTermTest extends TestCase
{
    public function testObjectPropertiesAndGetters(): void
    {
        $workdays = ['Monday', 'Tuesday'];
        $workdayStart = 9;
        $workdayEnd = 17;

        $subject = new WorkTerms($workdays, $workdayStart, $workdayEnd);

        $this->assertEquals($workdays, $subject->getWorkingDays());
        $this->assertEquals($workdayStart, $subject->getWorkdayStartHour());
        $this->assertEquals($workdayEnd, $subject->getWorkdayEndHour());
    }

    public function testItThrowsExceptionWhenWorkdaysNotProvided()
    {
        $this->expectException(WorkTermsException::class);
        $this->expectExceptionMessage('At least one working day needs to be provided!');

        new WorkTerms([], 9, 17);
    }

    #[DataProvider('invalidWorkingHourDataProvider')]
    public function testItThrowsExceptionWhenWorkHoursIsLessThanOne(
        int $workHourStart,
        int $workHourEnd,
        int $calculatedHours
    ) {
        $this->expectException(WorkTermsException::class);
        $this->expectExceptionMessage(
            sprintf('Working day length must be a positive integer! (Calculated: %d)', $calculatedHours)
        );

        new WorkTerms(['Monday'], $workHourStart, $workHourEnd);
    }

    public static function invalidWorkingHourDataProvider(): array
    {
        return [
            'work_hour_explicit_zero' => [9, 9, 0],
            'work_hour_negative' => [9, 5, -4],
        ];
    }
}
