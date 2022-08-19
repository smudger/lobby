<?php

namespace App\Domain\Exceptions;

use RuntimeException;

class LobbyNotAllocatedException extends RuntimeException
{
    protected $message = 'The lobby with the given ID has not been allocated.';
}
