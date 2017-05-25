<?php

namespace WA\Exceptions;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BadCriteriaException extends \Exception
{
    // Rethrow this so that Dingo can catch it
    public function __construct($message, $previous = null)
    {
        throw new BadRequestHttpException($message, $previous);
    }
}
