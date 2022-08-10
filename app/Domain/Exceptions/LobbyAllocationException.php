<?php

namespace App\Domain\Exceptions;

use Exception;

class LobbyAllocationException extends Exception
{
    protected $message = 'Failed to allocate lobby.';
}
