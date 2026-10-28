@extends('layouts.public')

@section('title', 'Pricing & Subscription Plans — Website Uptime Monitor')

@section('content')
    <section class="py-20 bg-white dark:bg-gray-950 border-b border-gray-200 dark:border-gray-800 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Predictable Billing</span>
            <h1 class="text-4xl sm:text-5xl font-black text-gray-950 dark:text-white mt-2 mb-4">
                Flexible Plans for Teams of Every Size
            </h1>
            <p class="text-base text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                No hidden fees. Upgrade, downgrade, or cancel at any time. All plans include global probe verification and instant alerts.
            </p>
        </div>
    </section>

    <section class="py-20 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach ($plans as $plan)
                    <div class="p-6 rounded-2xl border {{ $plan->is_featured ? 'border-blue-600 ring-2 ring-blue-600 bg-white dark:bg-gray-900 shadow-xl' : 'border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm' }} flex flex-col justify-between">
                        <div>
                            @if ($plan->is_featured)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 uppercase tracking-wider mb-3 inline-block">Popular</span>
                            @endif
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $plan->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1 mb-4 h-10">{{ $plan->description }}</p>

                            <div class="flex items-baseline gap-1 mb-6">
                                <span class="text-3xl font-black text-gray-950 dark:text-white">${{ number_format($plan->price_monthly, 0) }}</span>
                                <span class="text-xs text-gray-500">/ mo</span>
                            </div>

                            <ul class="space-y-2.5 text-xs text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800 pt-5">
                                <li>✓ <strong>{{ $plan->max_monitors }}</strong> monitors</li>
                                <li>✓ <strong>{{ $plan->min_check_interval_seconds }}s</strong> interval</li>
                                <li>✓ <strong>{{ $plan->max_status_pages }}</strong> status page</li>
                                <li>✓ <strong>{{ $plan->retention_days }}</strong> days history</li>
                                @if ($plan->allow_api)
                                    <li class="text-blue-600 dark:text-blue-400 font-semibold">✓ Developer API</li>
                                @endif
                                @if ($plan->allow_ai)
                                    <li class="text-blue-600 dark:text-blue-400 font-semibold">✓ AI Copilot</li>
                                @endif
                                @if ($plan->allow_agency)
                                    <li class="text-purple-600 dark:text-purple-400 font-semibold">✓ Agency Client Mode</li>
                                @endif
                            </ul>
                        </div>

                        <div class="mt-8 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <a href="{{ url('/onboarding') }}" class="block w-full py-2 text-center text-xs font-bold rounded-lg {{ $plan->is_featured ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-200' }} transition">
                                Choose {{ $plan->name }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

