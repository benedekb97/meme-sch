<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Entities\UserInterface;
use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;

class Terms
{
    private Guard $auth;

    public function __construct(
        AuthManager $authManager
    ) {
        $this->auth = $authManager->guard(config('auth.defaults.guard'));
    }

    public function handle($request, Closure $next)
    {
        /** @var UserInterface $user */
        $user = $this->auth->user();

        if (!isset($user)) {
            abort(401);
        }

        if (!$user->hasAcceptedTerms()) {
            return new RedirectResponse(route('terms'));
        }

        return $next($request);
    }
}
