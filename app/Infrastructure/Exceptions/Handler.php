<?php

namespace App\Infrastructure\Exceptions;

use App\Domain\Exceptions\ValidationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (ValidationException $e, Request $request) {
            return $this->shouldReturnJson($request, $e)
                ? response()->json([
                    'message' => $e->getMessage(),
                    'errors' => $e->errors,
                ], 422)
                : redirect(url()->previous())
                    ->withInput(Arr::except($request->input(), $this->dontFlash))
                    ->withErrors($e->errors);
        });
    }

    /**
     * Prepare exception for rendering.
     *
     * @return JsonResponse|RedirectResponse|Response|SymfonyResponse
     */
    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        /** @var Application $app */
        $app = app();
        /** @var bool $isLowerEnvironment */
        $isLowerEnvironment = $app->environment(['local', 'testing']);

        if (! $isLowerEnvironment && in_array($response->status(), [500, 503, 404, 403], true)) {
            return Inertia::render('Public/Error', ['status' => $response->status()])
                ->toResponse($request)
                ->setStatusCode($response->status());
        } elseif ($response->status() === 419) {
            return back()->with([
                'message' => 'The page expired, please try again.',
            ]);
        }

        return $response;
    }
}
