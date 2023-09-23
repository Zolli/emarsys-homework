<?php

namespace Zolli\Emarsys\Homework\Exception;

use Exception;

class WorkTermsException extends Exception
{
    public static function noWorkdayProvided(): self
    {
        return new self('At least one working day needs to be provided!');
    }

    public static function nonPositiveWorkHours(int $calculated): self
    {
        return new self(sprintf('Working day length must be a positive integer! (Calculated: %d)', $calculated));
    }
}
