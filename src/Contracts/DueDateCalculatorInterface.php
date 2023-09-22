<?php

declare(strict_types=1);

namespace Zolli\Emarsys\Homework\Contracts;

use \DateTimeImmutable;

interface DueDateCalculatorInterface
{
    public function calculateDueDate(DateTimeImmutable $submitDateTime, int $turnaroundTimeHours): DateTimeImmutable;
}
