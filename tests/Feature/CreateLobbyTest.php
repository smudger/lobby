<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

class CreateLobbyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_player_can_create_a_lobby(): void
    {
        Carbon::setTestNow(now());

        $response = $this->post('/lobbies', [
            'member_name' => 'Ayesha Nicole',
        ]);

        $this->assertDatabaseHas('lobbies', [
            'allocated_at' => now(),
        ]);

        /** @var \stdClass */
        $lobby = DB::table('lobbies')->whereNotNull('allocated_at')->first();
        Assert::assertEquals([
            ['name' => 'Ayesha Nicole'],
        ], json_decode($lobby->members, true));

        $response->assertRedirect(route('lobby.show', ['id' => $lobby->id]));
    }
}
