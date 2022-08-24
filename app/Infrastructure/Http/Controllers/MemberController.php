<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\CreateMemberCommand;
use App\Application\CreateMemberHandler;
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

        return Inertia::render('Member/Index', ['lobby' => [
            'id' => $lobby->id->__toString(),
            'members' => collect($lobby->members())
                ->map(fn (Member $member) => [
                    'name' => $member->name,
                ])
                ->toArray(),
        ]]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Member/Create', [
            'lobbyId' => $request->query('lobbyId'),
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        /** @var HasSession $user */
        $user = Auth::user();

        $user->logout($request->session());

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

            $handler->execute(new CreateMemberCommand(...$params));
        } catch (LobbyNotAllocatedException $e) {
            return back()->withErrors([
                'lobby_id' => trans('validation.exists', ['attribute' => 'lobby code']),
            ]);
        }

        $authFactory->createFromRaw([
            'lobby_id' => $params['lobby_id'],
            'member_id' => $params['name'],
        ])
            ->login($request->session());

        return redirect()->route('lobby.show', ['id' => $request->input('lobby_id')]);
    }
}
