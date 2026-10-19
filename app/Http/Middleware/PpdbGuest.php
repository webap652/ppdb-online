<?php

namespace App\Http\Middleware;

use App\Support\Ppdb;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PpdbGuest
{
    /** Pengguna yang sudah login diarahkan ke dasbornya masing-masing. */
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = Ppdb::user()) {
            return redirect()->route(Ppdb::homeRoute($user['role']));
        }

        return $next($request);
    }
}
