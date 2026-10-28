<?php

namespace App\Domain\Analytics\Services;

use Illuminate\Support\Facades\DB;

class PerformanceProfiler
{
    protected static array $queries = [];

    protected static bool $listening = false;

    /**
     * Start recording database queries for performance diagnostics.
     */
    public static function start(): void
    {
        if (static::$listening) {
            return;
        }

        static::$listening = true;
        static::$queries = [];

        DB::listen(function ($query) {
            static::$queries[] = [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time_ms' => $query->time,
            ];
        });
    }

    /**
     * Analyze recorded queries to detect N+1 bottlenecks and slow queries.
     */
    public static function analyze(): array
    {
        $totalQueries = count(static::$queries);
        $totalTime = 0.0;
        $slowQueries = [];
        $queryPatterns = [];

        foreach (static::$queries as $q) {
            $totalTime += $q['time_ms'];

            if ($q['time_ms'] > 50.0) {
                $slowQueries[] = $q;
            }

            // Normalize query to detect N+1
            $pattern = preg_replace('/\s+/', ' ', $q['sql']);
            $pattern = preg_replace('/(\?|\$\d+|\d+)/', '?', $pattern);
            $queryPatterns[$pattern] = ($queryPatterns[$pattern] ?? 0) + 1;
        }

        $nPlusOneSuspects = [];
        foreach ($queryPatterns as $pattern => $count) {
            if ($count >= 5) {
                $nPlusOneSuspects[] = [
                    'pattern' => $pattern,
                    'execution_count' => $count,
                    'suggestion' => 'Potential N+1 query. Consider eager loading relationships via Eloquent with().',
                ];
            }
        }

        return [
            'total_queries' => $totalQueries,
            'total_time_ms' => round($totalTime, 2),
            'slow_queries_count' => count($slowQueries),
            'slow_queries' => array_slice($slowQueries, 0, 5),
            'n_plus_one_warnings' => $nPlusOneSuspects,
            'memory_peak_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
            'status' => count($nPlusOneSuspects) === 0 && count($slowQueries) === 0 ? 'optimal' : 'attention_required',
        ];
    }
}
