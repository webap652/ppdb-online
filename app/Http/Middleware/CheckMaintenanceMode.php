<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        $enabled = filter_var(Setting::get('system.maintenance', false), FILTER_VALIDATE_BOOLEAN);

        // Pengguna yang sudah login (admin/operator) tetap bisa melihat situs
        if (! $enabled || $request->user()) {
            return $next($request);
        }

        return response()->view('maintenance', [], 503);
    }
}
