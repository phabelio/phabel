<?php

declare (strict_types=1);
namespace Phabel\PhpParser\ErrorHandler;

use Phabel\PhpParser\Error;
use Phabel\PhpParser\ErrorHandler;
/**
 * Error handler that handles all errors by throwing them.
 *
 * This is the default strategy used by all components.
 */
class Throwing implements ErrorHandler
{
    public function handleError(Error $error)
    {
        throw $error;
    }
}
