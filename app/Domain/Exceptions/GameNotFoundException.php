<?php

namespace App\Domain\Exceptions;

use RuntimeException;

class GameNotFoundException extends RuntimeException
{
    protected $message = 'Game not found.';
}
