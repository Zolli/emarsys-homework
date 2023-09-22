<?php

declare(strict_types=1);

namespace Zolli\Emarsys\Homework\Model;

use Zolli\Emarsys\Homework\Exception\WorkTermsException;

class WorkTerms
{
    public function __construct(
        private readonly array $workingDays,
        private readonly int $workdayStartHour,
        private readonly int $workdayEndHour
    ) {
        $this->assertAtLeastOneWorkingDayIsProvided();
        $this->assertPositiveWorkHours();
    }

    public function getWorkingDays(): array
    {
        return $this->workingDays;
    }

    public function getWorkdayStartHour(): int
    {
        return $this->workdayStartHour;
    }

    public function getWorkdayEndHour(): int
    {
        return $this->workdayEndHour;
    }

    /**
     * @throws WorkTermsException
     */
    private function assertAtLeastOneWorkingDayIsProvided(): void
    {
        if (count($this->workingDays) === 0) {
            throw WorkTermsException::noWorkdayProvided();
        }
    }

    /**
     * @throws WorkTermsException
     */
    private function assertPositiveWorkHours(): void
    {
        $workingHoursWithinADay = $this->workdayEndHour - $this->workdayStartHour;

        if ($workingHoursWithinADay <= 0) {
            throw WorkTermsException::nonPositiveWorkHours($workingHoursWithinADay);
        }
    }
}
