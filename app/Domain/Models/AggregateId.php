<?php

namespace App\Domain\Models;

interface AggregateId
{
    public function __toString(): string;
}
