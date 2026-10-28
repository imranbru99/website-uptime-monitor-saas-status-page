<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\StatusPages\Models\StatusPage;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatusPageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tenant = $request->user()->currentTenant();
        if (! $tenant) {
            return response()->json(['data' => []]);
        }

        $pages = StatusPage::where('tenant_id', $tenant->id)
            ->with(['components.monitor'])
            ->paginate($request->integer('per_page', 25));

        return response()->json($pages);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $tenant = $request->user()->currentTenant();
        $page = StatusPage::where('tenant_id', $tenant?->id)
            ->with(['components.monitor'])
            ->findOrFail($id);

        return response()->json([
            'data' => $page,
            'computed_status' => $page->computed_status,
        ]);
    }
}
