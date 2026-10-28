@extends('layouts.public')

@section('title', 'Website Uptime Monitor — Enterprise Observability, Alerts & Status Pages')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden pt-20 pb-28 lg:pt-28 lg:pb-36 bg-gradient-to-b from-blue-50/50 via-white to-gray-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-950/80 dark:text-blue-300 border border-blue-200 dark:border-blue-800 mb-6">
                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-ping"></span>
                    Next-Generation Enterprise Monitoring SaaS
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-950 dark:text-white tracking-tight leading-[1.15] mb-6">
                    Never lose customers to <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">silent downtime</span> again.
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed mb-8">
                    Continuous HTTP, API, SSL, DNS, TCP, and Server observability with sub-minute probes across 8 global regions, zero-false-positive incident confirmation, and beautiful status pages.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ url('/onboarding') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-blue-600 text-white font-bold text-sm shadow-lg shadow-blue-500/25 hover:bg-blue-700 transition hover:scale-[1.02]">
                        Start Free Trial (No Card Required)
                    </a>
                    <a href="{{ url('/demo') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 font-bold text-sm border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 transition flex items-center justify-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Explore Interactive Demo
                    </a>
                </div>
            </div>

            <!-- Live Telemetry Card Preview -->
            <div class="max-w-5xl mx-auto rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-2xl overflow-hidden">
                <!-- Mac-like Header -->
                <div class="px-5 py-3.5 bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-400"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                        <span class="text-xs font-mono text-gray-500 ml-2">global-probe-mesh.telemetry-node</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-500">
                        <span class="inline-flex items-center gap-1 font-mono text-emerald-600 dark:text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> 99.992% Uptime
                        </span>
                        <span>MTTD: 1.2m</span>
                        <span>MTTR: 6.4m</span>
                    </div>
                </div>

                <!-- Live Metrics Grid -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                    <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <span class="text-xs text-gray-500 block uppercase font-semibold">Active Probes</span>
                        <div class="text-2xl font-black text-gray-900 dark:text-white mt-1">100+ Live Targets</div>
                        <span class="text-xs text-emerald-600 mt-1 inline-block">✓ All multi-region checks active</span>
                    </div>
                    <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <span class="text-xs text-gray-500 block uppercase font-semibold">Global Latency (P90)</span>
                        <div class="text-2xl font-black text-gray-900 dark:text-white mt-1">42.8 ms</div>
                        <span class="text-xs text-blue-600 mt-1 inline-block">⚡ Tokyo, London, Singapore, NYC</span>
                    </div>
                    <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <span class="text-xs text-gray-500 block uppercase font-semibold">Incident Resolution</span>
                        <div class="text-2xl font-black text-gray-900 dark:text-white mt-1">99.98% SLO</div>
                        <span class="text-xs text-purple-600 mt-1 inline-block">🤖 Automated AI root-cause analysis</span>
                    </div>
                </div>

                <!-- Sample Probe Target List -->
                <div class="divide-y divide-gray-100 dark:divide-gray-800 text-xs">
                    <div class="px-6 py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <div>
                                <span class="font-bold text-gray-900 dark:text-white text-sm">Main Production Checkout API</span>
                                <span class="text-gray-400 font-mono ml-2">https://api.shopvista.example/v1/checkout</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <span class="font-mono text-gray-500">HTTP 200 • 38ms</span>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 font-semibold uppercase text-[10px]">Operational</span>
                        </div>
                    </div>
                    <div class="px-6 py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <div>
                                <span class="font-bold text-gray-900 dark:text-white text-sm">Auth Gateway (OAuth2 & SSO)</span>
                                <span class="text-gray-400 font-mono ml-2">https://auth.nimbuscloud.example/.well-known</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <span class="font-mono text-gray-500">HTTP 200 • 24ms</span>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 font-semibold uppercase text-[10px]">Operational</span>
                        </div>
                    </div>
                    <div class="px-6 py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <div>
                                <span class="font-bold text-gray-900 dark:text-white text-sm">Global DNS Propagation (Atlas Enterprise)</span>
                                <span class="text-gray-400 font-mono ml-2">NS1/NS2 authoritative records</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <span class="font-mono text-gray-500">DNS SOA Match • 12ms</span>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 font-semibold uppercase text-[10px]">Operational</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comprehensive Capabilities Section -->
    <section class="py-24 bg-white dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">One Platform. Every Monitoring Requirement.</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-950 dark:text-white mt-2">
                    60 Configurable SaaS Modules
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                    Activate exactly the modules your organization requires. Disabling unused features keeps your dashboard lean and fast without deleting historical data.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- 1: Website & API -->
                <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-blue-500 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-950 dark:text-blue-400 flex items-center justify-center font-bold mb-4">
                        🌐
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">HTTP, HTTPS & REST APIs</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                        Verify status codes, latency percentiles, JSON path assertions, payload schemas, and custom request headers with anti-SSRF protections.
                    </p>
                </div>

                <!-- 2: SSL & Domain -->
                <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-blue-500 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-950 dark:text-indigo-400 flex items-center justify-center font-bold mb-4">
                        🔒
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">SSL & Domain Expiration</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                        Prevent downtime from expired certificates or forgotten domain renewals with early automated warnings 30, 14, and 7 days prior.
                    </p>
                </div>

                <!-- 3: Heartbeat & Cron -->
                <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-blue-500 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400 flex items-center justify-center font-bold mb-4">
                        ⏱️
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Heartbeats & Cron Jobs</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                        Track scheduled cron jobs, database backups, and queue workers. Receive alerts when a scheduled job fails to report in on time.
                    </p>
                </div>

                <!-- 4: Incident Management -->
                <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-blue-500 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 dark:bg-rose-950 dark:text-rose-400 flex items-center justify-center font-bold mb-4">
                        🚨
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Incident Response & Postmortems</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                        Full incident lifecycle: auto-detection, acknowledgement, investigation timeline, customer updates, and executive postmortem templates.
                    </p>
                </div>

                <!-- 5: Public Status Pages -->
                <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-blue-500 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-950 dark:text-purple-400 flex items-center justify-center font-bold mb-4">
                        📢
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Public & Private Status Pages</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                        Custom domains, brand colors, service groups, 90-day uptime history bars, and email/webhook subscription engines.
                    </p>
                </div>

                <!-- 6: AI Incident Copilot -->
                <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-blue-500 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-950 dark:text-amber-400 flex items-center justify-center font-bold mb-4">
                        🤖
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">AI Operations Assistant</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                        AI-powered root-cause diagnosis, anomaly interpretation, and automated customer-facing status report generators grounded in verified telemetry.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Global Probe Mesh Section -->
    <section class="py-24 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Zero False Positives</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-950 dark:text-white mt-2">
                    Distributed Global Monitoring Locations
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                    Outages are independently verified across multiple geographic regions before triggering alarms, eliminating false alerts caused by transient network blips.
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                @foreach ($locations as $loc)
                    <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-center">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block mb-2"></span>
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ $loc->name }}</h4>
                        <span class="text-xs text-gray-400 font-mono uppercase">{{ $loc->code }} • {{ $loc->city }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Pricing Preview Section -->
    <section class="py-24 bg-white dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Transparent Pricing</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-950 dark:text-white mt-2">
                    Simple Plans for Every Stage of Growth
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                    From solo developers to enterprise infrastructure teams and multi-client digital agencies.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($plans->take(3) as $plan)
                    <div class="p-8 rounded-2xl border {{ $plan->is_featured ? 'border-blue-600 ring-2 ring-blue-600 bg-white dark:bg-gray-900 shadow-xl' : 'border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50' }} flex flex-col justify-between">
                        <div>
                            @if ($plan->is_featured)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 uppercase tracking-wider mb-4 inline-block">Most Popular</span>
                            @endif
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $plan->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1 mb-6">{{ $plan->description }}</p>

                            <div class="flex items-baseline gap-1 mb-6">
                                <span class="text-4xl font-black text-gray-950 dark:text-white">${{ number_format($plan->price_monthly, 0) }}</span>
                                <span class="text-xs text-gray-500">/ month</span>
                            </div>

                            <ul class="space-y-3 text-xs text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800 pt-6">
                                <li class="flex items-center gap-2">✓ <strong>{{ $plan->max_monitors }}</strong> active monitors</li>
                                <li class="flex items-center gap-2">✓ Checks down to <strong>{{ $plan->min_check_interval_seconds }}s</strong> intervals</li>
                                <li class="flex items-center gap-2">✓ <strong>{{ $plan->max_status_pages }}</strong> status page(s)</li>
                                <li class="flex items-center gap-2">✓ <strong>{{ $plan->retention_days }}</strong> days data retention</li>
                                @if ($plan->allow_ai)
                                    <li class="flex items-center gap-2 text-blue-600 dark:text-blue-400 font-semibold">✓ AI Incident Copilot included</li>
                                @endif
                                @if ($plan->allow_agency)
                                    <li class="flex items-center gap-2 text-purple-600 dark:text-purple-400 font-semibold">✓ Agency Multi-Client mode</li>
                                @endif
                            </ul>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800">
                            <a href="{{ url('/onboarding') }}" class="block w-full py-2.5 text-center text-xs font-bold rounded-lg {{ $plan->is_featured ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-300' }} transition">
                                Get Started
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Call to Action Banner -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl sm:text-4xl font-black mb-4">Ready to monitor your services with precision?</h2>
            <p class="text-base text-blue-100 mb-8 max-w-xl mx-auto">
                Set up your first monitor in under 60 seconds. Explore our simulated demo environment or connect your production web services immediately.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/onboarding') }}" class="px-8 py-3.5 rounded-xl bg-white text-blue-700 font-bold text-sm shadow-lg hover:bg-blue-50 transition">
                    Start Monitoring Now
                </a>
                <a href="{{ url('/demo') }}" class="px-8 py-3.5 rounded-xl bg-blue-800/60 text-white font-bold text-sm border border-blue-400/30 hover:bg-blue-800 transition">
                    Live Demo Walkthrough
                </a>
            </div>
        </div>
    </section>
@endsection
