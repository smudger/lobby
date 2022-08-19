<?php

namespace App\Domain\Exceptions;

use RuntimeException;

class NoMoreLobbiesException extends RuntimeException
{
    protected $message = 'There are currently no lobbies available for allocation.';
}
