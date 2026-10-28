<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Analytics\Services\UptimeCalculator;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    public function index(Request $request, UptimeCalculator $calculator): JsonResponse
    {
        $tenant = $request->user()->currentTenant();
        if (! $tenant) {
            return response()->json(['data' => []]);
        }

        $policies = $tenant->slaPolicies()->with('monitor')->get();
        $meanTimes = $calculator->calculateMeanTimes($tenant);

        return response()->json([
            'policies' => $policies,
            'metrics' => $meanTimes,
        ]);
    }
}
