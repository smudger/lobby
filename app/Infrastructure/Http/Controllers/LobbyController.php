<?php

namespace App\Infrastructure\Http\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class LobbyController extends Controller
{
    public function store(): Response|ResponseFactory
    {
        return response(status: 201);
    }
}
