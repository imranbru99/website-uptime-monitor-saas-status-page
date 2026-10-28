<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Monitoring\Models\MonitorLocation;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    public function index(): JsonResponse
    {
        $locations = MonitorLocation::orderBy('name')->get();

        return response()->json(['data' => $locations]);
    }
}
