<?php

declare(strict_types=1);

namespace Zolli\Emarsys\Homework;

use DateTimeImmutable;
use DateInterval;
use Zolli\Emarsys\Homework\Contracts\DueDateCalculatorInterface;
use Zolli\Emarsys\Homework\Exception\IssueException;
use Zolli\Emarsys\Homework\Model\WorkTerms;

class DueDateCalculator implements DueDateCalculatorInterface
{
    public function __construct(private readonly WorkTerms $workTerms) {}

    public function calculateDueDate(DateTimeImmutable $submitDateTime, int $turnaroundTimeHours): DateTimeImmutable
    {
        $this->assertSubmitDateIsValid($submitDateTime);
        $this->assertTurnaroundTimeIsValid($turnaroundTimeHours);

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
        return $this->isTimeInsideWorkingHours($dateTime) && $this->isDayAWorkday($dateTime);
    }

    private function isTimeInsideWorkingHours(DateTimeImmutable $dateTime): bool
    {
        $dateTimeHour = (int)$dateTime->format('G');

        return $dateTimeHour < $this->workTerms->getWorkdayEndHour()
            && $dateTimeHour >= $this->workTerms->getWorkdayStartHour();
    }

    private function isDayAWorkday(DateTimeImmutable $dateTime): bool
    {
        $dateTimeDayName = $dateTime->format('l');

        return in_array($dateTimeDayName, $this->workTerms->getWorkingDays(), true);
    }

    /**
     * @throws IssueException
     */
    private function assertSubmitDateIsValid(DateTimeImmutable $dateTime): void
    {
        if (!$this->isDateInsideWorkingHours($dateTime)) {
            throw IssueException::cannotSubmitOutsideWorkHours();
        }
    }

    /**
     * @throws IssueException
     */
    private function assertTurnaroundTimeIsValid(int $turnaroundTime): void
    {
        if ($turnaroundTime <= 0) {
            throw IssueException::turnaroundTimeMustBePositive();
        }
    }
}
