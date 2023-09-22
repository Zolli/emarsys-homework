<?php

declare(strict_types=1);

namespace Zolli\Emarsys\Homework;

use DateTimeImmutable;
use Zolli\Emarsys\Homework\Contracts\DueDateCalculatorInterface;

class DueDateCalculator implements DueDateCalculatorInterface
{
    public function calculateDueDate(DateTimeImmutable $submitDateTime, int $turnaroundTimeHours): DateTimeImmutable
    {
        // TODO: Implement calculateDueDate() method.
    }
}
