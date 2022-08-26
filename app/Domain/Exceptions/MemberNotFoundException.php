<?php

namespace App\Domain\Exceptions;

use RuntimeException;

class MemberNotFoundException extends RuntimeException
{
    protected $message = 'The member with the given id could not be found.';
}
