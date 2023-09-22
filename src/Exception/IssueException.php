<?php

declare(strict_types=1);

namespace Zolli\Emarsys\Homework\Exception;

use \Exception;

class IssueException extends Exception
{
    public static function cannotSubmitOutsideWorkHours(): self
    {
        return new self('Cannot submit issue outside work hours!');
    }

    public static function turnaroundTimeMustBePositive(): self
    {
        return new self('Turnaround time must be a positive integer!');
    }
}
