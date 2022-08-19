<?php

namespace App\Domain\Exceptions;

use RuntimeException;

class LobbyAllocationException extends RuntimeException
{
    protected $message = 'Failed to allocate lobby.';
}
