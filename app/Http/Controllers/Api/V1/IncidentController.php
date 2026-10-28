<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Incidents\Models\Incident;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tenant = $request->user()->currentTenant();
        if (! $tenant) {
            return response()->json(['data' => []]);
        }

        $incidents = Incident::where('tenant_id', $tenant->id)
            ->when($request->query('status'), fn ($q, $s) => $q->where('status', $s))
            ->when($request->query('severity'), fn ($q, $sev) => $q->where('severity', $sev))
            ->with(['monitor', 'acknowledgedBy', 'resolvedBy'])
            ->latest('detected_at')
            ->paginate($request->integer('per_page', 25));

        return response()->json($incidents);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $tenant = $request->user()->currentTenant();
        $incident = Incident::where('tenant_id', $tenant?->id)
            ->with(['monitor', 'updates', 'comments', 'postmortem'])
            ->findOrFail($id);

        return response()->json(['data' => $incident]);
    }

    public function acknowledge(Request $request, int $id): JsonResponse
    {
        $tenant = $request->user()->currentTenant();
        $incident = Incident::where('tenant_id', $tenant?->id)->findOrFail($id);

        $incident->acknowledge($request->user());

        return response()->json(['status' => 'acknowledged', 'data' => $incident]);
    }

    public function resolve(Request $request, int $id): JsonResponse
    {
        $tenant = $request->user()->currentTenant();
        $incident = Incident::where('tenant_id', $tenant?->id)->findOrFail($id);

        $validated = $request->validate([
            'summary' => 'nullable|string',
        ]);

        $incident->resolve($request->user(), $validated['summary'] ?? null);

        return response()->json(['status' => 'resolved', 'data' => $incident]);
    }
}
