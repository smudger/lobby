<?php

namespace App\Domain\Exceptions;

use Exception;

class LobbyNotAllocatedException extends Exception
{
    protected $message = 'The lobby with the given ID has not been allocated.';
}
