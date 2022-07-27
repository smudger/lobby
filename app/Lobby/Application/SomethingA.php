<?php

namespace App\Lobby\Application;

use App\Lobby\Domain\SomethingD;
use App\Lobby\Infrastructure\SomethingI;

class SomethingA
{
    public function __construct()
    {
        new SomethingD();
        new SomethingI();
    }

}
