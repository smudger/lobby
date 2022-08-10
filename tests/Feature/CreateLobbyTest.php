<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CreateLobbyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_lobby(): void
    {
        Carbon::setTestNow(now());

        $response = $this->post('/lobbies');

        $this->assertDatabaseHas('lobbies', [
            'allocated_at' => now(),
        ]);

        /** @var \stdClass */
        $lobby = DB::table('lobbies')->whereNotNull('allocated_at')->first();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Lobby/Show')
            ->where('code', $lobby->id)
        );
    }
}
