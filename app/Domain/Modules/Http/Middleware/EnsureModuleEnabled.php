<?php

namespace App\Domain\Modules\Http\Middleware;

use App\Domain\Modules\Services\ModuleManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleEnabled
{
    public function handle(Request $request, Closure $next, string $moduleSlug): Response
    {
        if (! ModuleManager::isEnabled($moduleSlug)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'module_disabled',
                    'message' => "The module [{$moduleSlug}] is currently disabled for this organization.",
                ], 403);
            }

            abort(403, "The requested module [{$moduleSlug}] is currently disabled for this organization.");
        }

        return $next($request);
    }
}
