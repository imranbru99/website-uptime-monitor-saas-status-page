@extends('layouts.public')

@section('title', 'Interactive Demo — Website Uptime Monitor')

@section('content')
    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Demo Header Banner -->
            <div class="mb-10 p-6 rounded-2xl bg-gradient-to-r from-blue-900 to-indigo-950 text-white border border-blue-800 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Simulated Enterprise Sandbox
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black">Interactive Platform Demonstration</h1>
                    <p class="text-xs text-blue-200 mt-1 max-w-xl">
                        Explore four pre-seeded business environments with synthetic check histories, realistic incident timelines, global probe metrics, and module manager customization.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ url('/app/login') }}" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md transition">
                        Log In to App Panel (/app)
                    </a>
                    <a href="{{ url('/platform/login') }}" class="px-4 py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-200 font-bold text-xs border border-gray-700 transition">
                        Super Admin Panel (/platform)
                    </a>
                </div>
            </div>

            <!-- Demo Tenant Showcases -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <!-- Demo A: SaaS -->
                <div class="p-5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300">SaaS Company</span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white mt-2 mb-1">NimbusCloud Technologies</h3>
                        <p class="text-xs text-gray-500 mb-4">API gateways, Kubernetes probes, AI assistant copilot, and multi-region failover tests.</p>
                        <div class="text-[11px] text-gray-400 space-y-1">
                            <div>• Branches: Singapore HQ, London, NYC</div>
                            <div>• Uptime: 99.98% • Latency: 32ms</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center text-xs font-semibold text-blue-600">
                        <a href="{{ url('/status/nimbuscloud-status') }}" target="_blank" class="hover:underline">View Status Page ↗</a>
                    </div>
                </div>

                <!-- Demo B: Ecommerce -->
                <div class="p-5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">Ecommerce</span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white mt-2 mb-1">ShopVista Commerce</h3>
                        <p class="text-xs text-gray-500 mb-4">Checkout sandbox funnels, SSL renewal trackers, payment webhook monitors.</p>
                        <div class="text-[11px] text-gray-400 space-y-1">
                            <div>• Branches: Dhaka, Singapore, London</div>
                            <div>• Uptime: 99.95% • Latency: 48ms</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center text-xs font-semibold text-emerald-600">
                        <a href="{{ url('/status/shopvista-status') }}" target="_blank" class="hover:underline">View Status Page ↗</a>
                    </div>
                </div>

                <!-- Demo C: Digital Agency -->
                <div class="p-5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300">Agency & MSP</span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white mt-2 mb-1">PixelForge Digital Agency</h3>
                        <p class="text-xs text-gray-500 mb-4">White-label reports, dedicated client portals, multi-tenant billing.</p>
                        <div class="text-[11px] text-gray-400 space-y-1">
                            <div>• Clients: 12 Organizations</div>
                            <div>• Client Portal: /client</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center text-xs font-semibold text-purple-600">
                        <a href="{{ url('/client/login') }}" class="hover:underline">Client Portal ↗</a>
                    </div>
                </div>

                <!-- Demo D: Enterprise IT -->
                <div class="p-5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">Enterprise IT</span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white mt-2 mb-1">Atlas Enterprise Systems</h3>
                        <p class="text-xs text-gray-500 mb-4">Private agent probes, SLA burn-rate tracking, workforce shift on-call coordination.</p>
                        <div class="text-[11px] text-gray-400 space-y-1">
                            <div>• Branches: HQ & Regional Data Centers</div>
                            <div>• Operations Panel: /operations</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center text-xs font-semibold text-amber-600">
                        <a href="{{ url('/operations/login') }}" class="hover:underline">Operations Panel ↗</a>
                    </div>
                </div>
            </div>

            <!-- Demo Seeded Accounts Table -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm mb-12">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">Documented Demo Test Accounts</h3>
                <p class="text-xs text-gray-500 mb-6">Use any of the seeded demonstration credentials to log in and test specific roles.</p>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-500 font-mono uppercase border-b border-gray-200 dark:border-gray-800">
                            <tr>
                                <th class="p-3">Role</th>
                                <th class="p-3">Login Email</th>
                                <th class="p-3">Default Password</th>
                                <th class="p-3">Panel Route</th>
                                <th class="p-3">Permissions Scope</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <tr>
                                <td class="p-3 font-bold text-red-600">Platform Super Admin</td>
                                <td class="p-3 font-mono">superadmin@demo.example</td>
                                <td class="p-3 font-mono">password</td>
                                <td class="p-3"><a href="{{ url('/platform') }}" class="text-blue-600 hover:underline">/platform</a></td>
                                <td class="p-3 text-gray-500">Cross-tenant superadmin, plans, modules, locations</td>
                            </tr>
                            <tr>
                                <td class="p-3 font-bold text-blue-600">Tenant Owner</td>
                                <td class="p-3 font-mono">owner@demo.example</td>
                                <td class="p-3 font-mono">password</td>
                                <td class="p-3"><a href="{{ url('/app') }}" class="text-blue-600 hover:underline">/app</a></td>
                                <td class="p-3 text-gray-500">Full tenant control, billing, module manager, monitors</td>
                            </tr>
                            <tr>
                                <td class="p-3 font-bold text-emerald-600">Branch Manager</td>
                                <td class="p-3 font-mono">branch.manager@demo.example</td>
                                <td class="p-3 font-mono">password</td>
                                <td class="p-3"><a href="{{ url('/branch') }}" class="text-blue-600 hover:underline">/branch</a></td>
                                <td class="p-3 text-gray-500">Regional branch scoping, branch monitors, local teams</td>
                            </tr>
                            <tr>
                                <td class="p-3 font-bold text-indigo-600">Agency Client</td>
                                <td class="p-3 font-mono">client@demo.example</td>
                                <td class="p-3 font-mono">password</td>
                                <td class="p-3"><a href="{{ url('/client') }}" class="text-blue-600 hover:underline">/client</a></td>
                                <td class="p-3 text-gray-500">Client portal view, white-label client status page</td>
                            </tr>
                            <tr>
                                <td class="p-3 font-bold text-rose-600">SRE / Operations</td>
                                <td class="p-3 font-mono">engineer@demo.example</td>
                                <td class="p-3 font-mono">password</td>
                                <td class="p-3"><a href="{{ url('/operations') }}" class="text-blue-600 hover:underline">/operations</a></td>
                                <td class="p-3 text-gray-500">On-call alerts, incident triage, live probe telemetry</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
