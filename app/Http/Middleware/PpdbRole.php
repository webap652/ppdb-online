<?php

namespace App\Http\Middleware;

use App\Support\Ppdb;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PpdbRole
{
    /**
     * Pemakaian: ->middleware('ppdb.role:admin') atau ->middleware('ppdb.role:admin,operator')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Ppdb::user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        if (! in_array($user['role'], $roles, true)) {
            abort(403, 'Anda tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}
