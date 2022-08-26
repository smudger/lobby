<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\CreateMemberCommand;
use App\Application\CreateMemberHandler;
use App\Application\DestroyMemberCommand;
use App\Application\DestroyMemberHandler;
use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Models\LobbyId;
use App\Domain\Models\Member;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Auth\HasSession;
use App\Infrastructure\Auth\UserFactory;
use App\Infrastructure\Http\Requests\CreateMemberRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class MemberController extends Controller
{
    public function index(string $id, LobbyRepository $repository): Response
    {
        try {
            $lobby = $repository->findById(LobbyId::fromString($id));
        } catch (LobbyNotAllocatedException) {
            abort(404);
        }

        /** @var \stdClass $me */
        $me = Auth::user();

        return Inertia::render('Member/Index', [
            'lobby' => [
                'id' => $lobby->id->__toString(),
                'members' => collect($lobby->members())
                    ->map(fn (Member $member) => [
                        'name' => $member->name,
                        'id' => $member->id,
                    ])
                    ->toArray(),
            ],
            'me' => [
                'member_id' => $me->member_id,
            ],
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Member/Create', [
            'lobbyId' => $request->query('lobbyId'),
        ]);
    }

    public function destroy(
        Request $request,
        DestroyMemberHandler $handler,
    ): RedirectResponse {
        /** @var HasSession $user */
        $user = Auth::user();

        $user->logout($request->session());

        $command = new DestroyMemberCommand(
            lobby_id: $user->lobby_id,
            member_id: $user->member_id,
        );

        $handler->execute($command);

        return redirect()->route('home');
    }

    public function store(
        CreateMemberRequest $request,
        CreateMemberHandler $handler,
        UserFactory $authFactory,
    ): RedirectResponse {
        try {
            /** @var string[] $params */
            $params = $request->validated();

            $memberId = $handler->execute(new CreateMemberCommand(...$params));
        } catch (LobbyNotAllocatedException $e) {
            return back()->withErrors([
                'lobby_id' => trans('validation.exists', ['attribute' => 'lobby code']),
            ]);
        }

        $authFactory->createFromRaw([
            'lobby_id' => $params['lobby_id'],
            'member_id' => $memberId,
        ])
            ->login($request->session());

        return redirect()->route('lobby.show', ['id' => $request->input('lobby_id')]);
    }
}
