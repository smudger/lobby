<?php

namespace App\Domain\Exceptions;

use Exception;

class NoMoreLobbiesException extends Exception
{
    protected $message = 'There are currently no lobbies available for allocation.';
}
