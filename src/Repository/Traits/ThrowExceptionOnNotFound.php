<?php

namespace App\Repository\Traits;

use App\Exception\NotFoundException;

/**
 * Default behaviour is returning null; call the setter in case exception throwing is needed.
 */
trait ThrowExceptionOnNotFound
{
    private $throwExceptionOnNotFound = false;
    private $exceptionClass = NotFoundException::class;
}
