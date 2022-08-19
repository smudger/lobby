<?php

namespace App\Domain\Exceptions;

use RuntimeException;

class ValidationException extends RuntimeException
{
    protected $message = 'The given data was invalid.';

    public function __construct(
        /** @var array<string, string[]> $errors */
        public readonly array $errors,
    ) {
        parent::__construct();
    }
}
