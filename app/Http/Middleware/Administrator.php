<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Entities\UserInterface;
use Closure;
use Illuminate\Support\Facades\Auth;

class Administrator
{
    public function handle($request, Closure $next)
    {
        /** @var UserInterface $user */
        $user = Auth::user();

        if ($user === null) {
            abort(403);
        }

        if (!$user->isAdministrator() && !$user->isGroupLeader()) {
            abort(401);
        }

        return $next($request);
    }
}
