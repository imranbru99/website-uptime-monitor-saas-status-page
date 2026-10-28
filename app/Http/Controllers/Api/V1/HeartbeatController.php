<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Monitoring\Models\Heartbeat;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HeartbeatController extends Controller
{
    /**
     * Ingest heartbeat pulse from cron job, worker, or daemon.
     */
    public function ping(Request $request, string $token): JsonResponse
    {
        $heartbeat = Heartbeat::where('secret_token', $token)->first();

        if (! $heartbeat) {
            return response()->json([
                'error' => 'not_found',
                'message' => 'Heartbeat secret token not recognized.',
            ], 404);
        }

        $now = now();
        $nextDue = $now->copy()->addSeconds($heartbeat->interval_seconds + $heartbeat->grace_seconds);

        $heartbeat->update([
            'last_ping_at' => $now,
            'next_expected_at' => $nextDue,
            'status' => 'healthy',
        ]);

        $monitor = $heartbeat->monitor;
        if ($monitor && $monitor->status !== 'healthy') {
            $monitor->update([
                'status' => 'healthy',
                'last_checked_at' => $now,
                'consecutive_successes' => $monitor->consecutive_successes + 1,
                'consecutive_failures' => 0,
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Heartbeat pulse acknowledged.',
            'timestamp' => $now->toIso8601String(),
            'next_expected_at' => $nextDue->toIso8601String(),
        ]);
    }
}
