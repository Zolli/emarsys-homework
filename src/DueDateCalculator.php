<?php

declare(strict_types=1);

namespace Zolli\Emarsys\Homework;

use \DateTimeImmutable;
use \DateInterval;
use Zolli\Emarsys\Homework\Contracts\DueDateCalculatorInterface;
use Zolli\Emarsys\Homework\Model\WorkTerms;

class DueDateCalculator implements DueDateCalculatorInterface
{
    public function __construct(private readonly WorkTerms $workTerms) {}

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

        return ($dateTimeHour < $this->workTerms->getWorkdayEndHour() && $dateTimeHour >= $this->workTerms->getWorkdayStartHour())
            && in_array($dateTimeDayName, $this->workTerms->getWorkingDays());
    }
}
