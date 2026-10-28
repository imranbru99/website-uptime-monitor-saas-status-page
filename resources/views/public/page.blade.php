@extends('layouts.public')

@section('title', $title . ' — Website Uptime Monitor')

@section('content')
    <div class="py-16 bg-white dark:bg-gray-950 border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Enterprise Capability</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-950 dark:text-white mt-2 mb-4">{{ $title }}</h1>
            <p class="text-base text-gray-600 dark:text-gray-300 leading-relaxed">{{ $summary }}</p>
        </div>
    </div>

    <div class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white dark:bg-gray-950 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 shadow-sm space-y-6 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                @if ($content)
                    {!! $content !!}
                @else
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Continuous Reliability & Automated Verification</h2>
                    <p>
                        The <strong>{{ $title }}</strong> module provides high-frequency distributed probing, sub-second telemetry ingestion, and automated confirmation logic designed specifically to prevent false alerts.
                    </p>

                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-6">Core Architectural Highlights</h3>
                    <ul class="space-y-2 list-disc pl-5">
                        <li>Multi-region verification across Singapore, Tokyo, London, Frankfurt, New York, Mumbai, Sydney, and São Paulo.</li>
                        <li>Configurable latency percentile calculations (P50, P90, P95, P99) and moving average tracking.</li>
                        <li>Automated tiered escalation with on-call rotations and quiet-hours alert suppression.</li>
                        <li>Zero-configuration status page publishing with custom domain SSL verification.</li>
                    </ul>

                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-500">Ready to test {{ strtolower($title) }}?</span>
                            <div class="text-sm font-bold text-gray-900 dark:text-white">Start your 14-day full access trial</div>
                        </div>
                        <a href="{{ url('/onboarding') }}" class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition">
                            Get Started Free
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
