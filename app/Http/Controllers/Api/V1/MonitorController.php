<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Services\CheckRunner;
use App\Domain\Monitoring\Services\SSRFValidator;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class MonitorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tenant = $request->user()->currentTenant();
        if (! $tenant) {
            return response()->json(['data' => []]);
        }

        $monitors = Monitor::where('tenant_id', $tenant->id)
            ->when($request->query('status'), fn ($q, $s) => $q->where('status', $s))
            ->when($request->query('type'), fn ($q, $t) => $q->where('monitor_type', $t))
            ->with(['latestCheck'])
            ->paginate($request->integer('per_page', 25));

        return response()->json($monitors);
    }

    public function store(Request $request): JsonResponse
    {
        $tenant = $request->user()->currentTenant();
        if (! $tenant) {
            return response()->json(['error' => 'no_active_tenant', 'message' => 'No active tenant organization found.'], 400);
        }

        if (! $tenant->canAddMonitor()) {
            return response()->json(['error' => 'quota_exceeded', 'message' => 'Your subscription monitor limit has been reached.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target' => 'required|string|max:500',
            'monitor_type' => 'required|string|in:http,https,api,tcp,dns,ssl,domain,ping,port,keyword,synthetic,heartbeat,server,database',
            'check_interval_seconds' => 'nullable|integer|min:10|max:86400',
            'timeout_seconds' => 'nullable|integer|min:2|max:60',
            'confirmation_threshold' => 'nullable|integer|min:1|max:10',
            'tags' => 'nullable|array',
            'config' => 'nullable|array',
        ]);

        try {
            SSRFValidator::validate($validated['target']);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'ssrf_security_violation',
                'message' => $e->getMessage(),
            ], 422);
        }

        $monitor = Monitor::create([
            'tenant_id' => $tenant->id,
            'branch_id' => $request->user()->current_branch_id,
            'name' => $validated['name'],
            'target' => $validated['target'],
            'monitor_type' => $validated['monitor_type'],
            'check_interval_seconds' => $validated['check_interval_seconds'] ?? 60,
            'timeout_seconds' => $validated['timeout_seconds'] ?? 15,
            'confirmation_threshold' => $validated['confirmation_threshold'] ?? 2,
            'status' => 'pending',
            'tags' => $validated['tags'] ?? [],
            'config' => $validated['config'] ?? [],
            'next_check_due_at' => now(),
        ]);

        return response()->json([
            'status' => 'created',
            'data' => $monitor,
        ], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $tenant = $request->user()->currentTenant();
        $monitor = Monitor::where('tenant_id', $tenant?->id)->with(['latestCheck'])->findOrFail($id);

        return response()->json(['data' => $monitor]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $tenant = $request->user()->currentTenant();
        $monitor = Monitor::where('tenant_id', $tenant?->id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'target' => 'sometimes|string|max:500',
            'check_interval_seconds' => 'sometimes|integer|min:10',
            'timeout_seconds' => 'sometimes|integer|min:2',
            'is_active' => 'sometimes|boolean',
            'is_paused' => 'sometimes|boolean',
            'tags' => 'sometimes|array',
            'config' => 'sometimes|array',
        ]);

        if (isset($validated['target']) && $validated['target'] !== $monitor->target) {
            try {
                SSRFValidator::validate($validated['target']);
            } catch (Throwable $e) {
                return response()->json(['error' => 'ssrf_security_violation', 'message' => $e->getMessage()], 422);
            }
        }

        $monitor->update($validated);

        return response()->json(['status' => 'updated', 'data' => $monitor]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $tenant = $request->user()->currentTenant();
        $monitor = Monitor::where('tenant_id', $tenant?->id)->findOrFail($id);
        $monitor->delete();

        return response()->json(['status' => 'deleted']);
    }

    public function checkNow(Request $request, int $id, CheckRunner $runner): JsonResponse
    {
        $tenant = $request->user()->currentTenant();
        $monitor = Monitor::where('tenant_id', $tenant?->id)->findOrFail($id);

        $check = $runner->execute($monitor);

        return response()->json([
            'status' => 'completed',
            'check' => $check,
            'monitor_status' => $monitor->fresh()->status,
        ]);
    }

    public function checks(Request $request, int $id): JsonResponse
    {
        $tenant = $request->user()->currentTenant();
        $monitor = Monitor::where('tenant_id', $tenant?->id)->findOrFail($id);

        $checks = $monitor->checks()
            ->latest('checked_at')
            ->paginate($request->integer('per_page', 50));

        return response()->json($checks);
    }
}
