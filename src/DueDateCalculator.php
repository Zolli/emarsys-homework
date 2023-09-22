<?php

declare(strict_types=1);

namespace Zolli\Emarsys\Homework;

use \DateTimeImmutable;
use \DateInterval;
use Zolli\Emarsys\Homework\Contracts\DueDateCalculatorInterface;

class DueDateCalculator implements DueDateCalculatorInterface
{
    public function __construct(int $a)
    {

    }

    public function calculateDueDate(DateTimeImmutable $submitDateTime, int $turnaroundTimeHours): DateTimeImmutable
    {
        $dueDateTime = clone $submitDateTime;

        while ($turnaroundTimeHours > 0) {
            $dueDateTime = $dueDateTime->add(DateInterval::createFromDateString('1 hour'));


            if ($this->isDateInsideWorkingHours($dueDateTime)) {
                $turnaroundTimeHours--;
            }
        }

        return $dueDateTime;
    }

    private function isDateInsideWorkingHours(DateTimeImmutable $dateTime): bool
    {
        $dateTimeHour = (int)$dateTime->format('G');
        $dateTimeDayName = $dateTime->format('l');

        return ($dateTimeHour < self::WORKDAY_END && $dateTimeHour >= self::WORKDAY_START)
            && in_array($dateTimeDayName, self::WORKDAYS);
    }
}
