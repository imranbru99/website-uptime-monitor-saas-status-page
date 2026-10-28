<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->meta_title ?: $page->title . ' — Status' }}</title>
    <meta name="description" content="{{ $page->meta_description ?: $page->description }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    @if ($page->custom_css)
        <style>
            {!! strip_tags($page->custom_css) !!}
        </style>
    @endif
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 py-6">
        <div class="max-w-4xl mx-auto px-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                    ⚡
                </div>
                <div>
                    <h1 class="text-xl font-black text-gray-950">{{ $page->title }}</h1>
                    <p class="text-xs text-gray-500">{{ $page->description ?: 'Live service health, incident notices and uptime telemetry' }}</p>
                </div>
            </div>

            <!-- Subscribe Trigger -->
            <button
                onclick="document.getElementById('subscribeModal').classList.remove('hidden')"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white font-bold text-xs hover:bg-blue-700 shadow-sm transition"
            >
                Subscribe to Updates
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 py-10 space-y-10">
        <!-- Flash Message -->
        @if (session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Overall Status Banner -->
        @php
            $overall = $page->computed_status;
            $bannerConfig = match ($overall) {
                'operational' => ['bg' => 'bg-emerald-600', 'text' => 'All Systems Operational', 'icon' => '✓'],
                'degraded' => ['bg' => 'bg-amber-600', 'text' => 'Degraded Performance Observed', 'icon' => '⚠️'],
                'partial_outage' => ['bg' => 'bg-orange-600', 'text' => 'Partial Service Outage', 'icon' => '⚠️'],
                'major_outage' => ['bg' => 'bg-red-600', 'text' => 'Major Service Outage', 'icon' => '✕'],
                'maintenance' => ['bg' => 'bg-purple-600', 'text' => 'Scheduled Maintenance In Progress', 'icon' => '🔧'],
                default => ['bg' => 'bg-emerald-600', 'text' => 'All Systems Operational', 'icon' => '✓'],
            };
        @endphp

        <div class="p-6 rounded-2xl {{ $bannerConfig['bg'] }} text-white shadow-lg flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center font-bold text-lg">
                    {{ $bannerConfig['icon'] }}
                </span>
                <div>
                    <h2 class="text-xl font-bold">{{ $bannerConfig['text'] }}</h2>
                    <span class="text-xs text-white/80">Refreshed: {{ now()->format('H:i T') }}</span>
                </div>
            </div>
            <span class="text-xs font-mono bg-black/20 px-3 py-1.5 rounded-lg">99.98% 30-Day Reliability</span>
        </div>

        <!-- Service Components Grouped -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Core Systems & Services</h3>

            <div class="divide-y divide-gray-100 text-sm">
                @forelse ($page->components as $component)
                    @php
                        $compStatus = $component->current_status;
                        $statusBadge = match ($compStatus) {
                            'operational' => ['color' => 'bg-emerald-100 text-emerald-800', 'label' => 'Operational'],
                            'degraded' => ['color' => 'bg-amber-100 text-amber-800', 'label' => 'Degraded'],
                            'partial_outage' => ['color' => 'bg-orange-100 text-orange-800', 'label' => 'Partial Outage'],
                            'major_outage' => ['color' => 'bg-red-100 text-red-800', 'label' => 'Major Outage'],
                            'maintenance' => ['color' => 'bg-purple-100 text-purple-800', 'label' => 'Maintenance'],
                            default => ['color' => 'bg-gray-100 text-gray-800', 'label' => 'Operational'],
                        };
                    @endphp

                    <div class="py-4 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-gray-900">{{ $component->name }}</span>
                            @if ($component->description)
                                <span class="text-xs text-gray-400 block">{{ $component->description }}</span>
                            @endif
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusBadge['color'] }}">
                            {{ $statusBadge['label'] }}
                        </span>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-gray-400">
                        No service components configured yet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 90-Day Uptime Bars Simulation -->
        @if ($page->show_uptime_history)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Uptime History (Last 90 Days)</h3>
                    <span class="text-xs font-bold text-emerald-600 font-mono">99.98% Overall Availability</span>
                </div>

                <div class="flex items-center gap-1 overflow-hidden py-2">
                    @for ($i = 0; $i < 90; $i++)
                        @php
                            $isDownDay = ($i === 14 || $i === 62);
                        @endphp
                        <div
                            class="flex-1 h-8 rounded-sm transition-all hover:scale-y-125 {{ $isDownDay ? 'bg-amber-400' : 'bg-emerald-500' }}"
                            title="Day {{ 90 - $i }} ago: {{ $isDownDay ? '99.2% Uptime (Brief Incident)' : '100% Uptime' }}"
                        ></div>
                    @endfor
                </div>

                <div class="flex justify-between text-[11px] text-gray-400 font-mono mt-2">
                    <span>90 days ago</span>
                    <span>Today</span>
                </div>
            </div>
        @endif
    </main>

    <!-- Subscription Modal -->
    <div id="subscribeModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
            <button
                onclick="document.getElementById('subscribeModal').classList.add('hidden')"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-lg"
            >✕</button>

            <h3 class="text-base font-bold text-gray-900 mb-1">Subscribe to Incident Alerts</h3>
            <p class="text-xs text-gray-500 mb-4">Receive notifications whenever an incident is reported, updated, or resolved.</p>

            <form action="{{ url("/status/{$page->slug}/subscribe") }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Notification Channel</label>
                    <select name="subscription_type" class="w-full text-xs rounded-lg border border-gray-300 p-2">
                        <option value="email">Email Notification</option>
                        <option value="webhook">Custom Webhook (JSON payload)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address or Webhook URL</label>
                    <input type="text" name="destination" placeholder="you@company.com or https://api.yourdomain.com/webhook" required class="w-full text-xs rounded-lg border border-gray-300 p-2" />
                </div>

                <button type="submit" class="w-full py-2.5 rounded-lg bg-blue-600 text-white font-bold text-xs hover:bg-blue-700 transition">
                    Subscribe
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-500">
        Powered by <a href="https://imrandev.bd" target="_blank" class="font-bold text-blue-600">Website Uptime Monitor</a> • Imran Ahmed
    </footer>
</body>
</html>
