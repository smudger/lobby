<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\CreateMemberCommand;
use App\Application\CreateMemberHandler;
use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Infrastructure\Http\Requests\CreateMemberRequest;
use Illuminate\Http\RedirectResponse;

class MemberController extends Controller
{
    public function store(
        CreateMemberRequest $request,
        CreateMemberHandler $handler,
    ): RedirectResponse {
        try {
            /** @var string[] $params */
            $params = $request->validated();

            $handler->execute(new CreateMemberCommand(...$params));
        } catch (LobbyNotAllocatedException $e) {
            return back()->withErrors([
                'lobby_id' => trans('validation.exists', ['attribute' => 'lobby code']),
            ]);
        }

        return redirect()->route('lobby.show', ['id' => $request->input('lobby_id')]);
    }
}
