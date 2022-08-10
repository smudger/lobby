<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateLobbyTest extends TestCase
{
    /** @test */
    public function it_creates_a_lobby(): void
    {
        $response = $this->post('/lobbies');

        $this->assertDatabaseCount('lobbies', 1);
        $response->assertStatus(201);
    }
}
