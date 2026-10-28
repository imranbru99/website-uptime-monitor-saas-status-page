@extends('layouts.public')

@section('title', 'Get Started — Website Uptime Monitor Onboarding')

@section('content')
    <div class="py-16 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto px-4">
            <div class="text-center mb-10">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Step-by-Step Onboarding</span>
                <h1 class="text-3xl font-extrabold text-gray-950 dark:text-white mt-1">Set Up Your Monitoring Workspace</h1>
                <p class="text-xs text-gray-500 mt-2">Deploy your organization and activate your first monitor in under 60 seconds.</p>
            </div>

            <div class="bg-white dark:bg-gray-950 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 shadow-sm">
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('/onboarding') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Organization Info -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">
                            1. Business Details
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Company / Organization Name</label>
                                <input type="text" name="business_name" value="{{ old('business_name') }}" placeholder="Acme Technologies" required class="w-full text-xs rounded-lg border border-gray-300 dark:border-gray-700 p-2.5 bg-gray-50 dark:bg-gray-900" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Operational Business Mode</label>
                                <select name="business_mode" class="w-full text-xs rounded-lg border border-gray-300 dark:border-gray-700 p-2.5 bg-gray-50 dark:bg-gray-900">
                                    <option value="saas">SaaS Platform</option>
                                    <option value="ecommerce">Ecommerce Business</option>
                                    <option value="agency">Digital Agency / MSP</option>
                                    <option value="enterprise">Enterprise IT</option>
                                    <option value="freelancer">Freelancer / Consultant</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Administrator Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required class="w-full text-xs rounded-lg border border-gray-300 dark:border-gray-700 p-2.5 bg-gray-50 dark:bg-gray-900" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Password</label>
                                <input type="password" name="password" placeholder="••••••••" required class="w-full text-xs rounded-lg border border-gray-300 dark:border-gray-700 p-2.5 bg-gray-50 dark:bg-gray-900" />
                            </div>
                        </div>
                    </div>

                    <!-- First Monitor -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">
                            2. First Monitored Target
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Target Name</label>
                                <input type="text" name="monitor_name" value="{{ old('monitor_name', 'Primary Website') }}" placeholder="Primary Website" required class="w-full text-xs rounded-lg border border-gray-300 dark:border-gray-700 p-2.5 bg-gray-50 dark:bg-gray-900" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Target URL / Host</label>
                                <input type="text" name="target_url" value="{{ old('target_url', 'https://example.com') }}" placeholder="https://example.com" required class="w-full text-xs rounded-lg border border-gray-300 dark:border-gray-700 p-2.5 bg-gray-50 dark:bg-gray-900" />
                            </div>
                        </div>
                    </div>

                    <!-- Plan Selection -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">
                            3. Select Trial Subscription Plan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @foreach ($plans->take(3) as $plan)
                                <label class="p-4 rounded-xl border border-gray-200 dark:border-gray-800 cursor-pointer hover:border-blue-500 block">
                                    <input type="radio" name="plan_id" value="{{ $plan->id }}" {{ $loop->first ? 'checked' : '' }} class="mr-2" />
                                    <span class="font-bold text-xs text-gray-900 dark:text-white">{{ $plan->name }}</span>
                                    <span class="text-xs text-gray-500 block mt-1">${{ number_format($plan->price_monthly, 0) }}/mo • {{ $plan->max_monitors }} monitors</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/30 transition hover:scale-[1.01]">
                        Complete Setup & Launch Dashboard →
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

