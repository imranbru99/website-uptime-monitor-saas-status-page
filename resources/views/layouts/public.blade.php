<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Website Uptime Monitor — Enterprise Observability & Status Pages')</title>
    <meta name="description" content="@yield('meta_description', 'Enterprise Website Monitoring, Infrastructure Observability, Incident Management, Automated Alerts, and Public Status Pages SaaS.')">
    <meta name="author" content="Imran Ahmed (https://imrandev.bd)">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#172554',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100 antialiased font-sans flex flex-col min-h-screen">
    <!-- Navigation Bar -->
    <header class="sticky top-0 z-50 bg-white/80 dark:bg-gray-950/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-base font-extrabold tracking-tight text-gray-950 dark:text-white">Uptime<span class="text-blue-600 dark:text-blue-400">Monitor</span></span>
                            <span class="hidden sm:inline-block ml-1.5 px-1.5 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase bg-blue-50 text-blue-700 dark:bg-blue-950/80 dark:text-blue-300 border border-blue-200/50 dark:border-blue-800/50">Enterprise</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-gray-600 dark:text-gray-300">
                    <a href="{{ url('/features') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Features</a>
                    <a href="{{ url('/solutions/enterprise') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Enterprise</a>
                    <a href="{{ url('/solutions/agency') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Agency</a>
                    <a href="{{ url('/integrations') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Integrations</a>
                    <a href="{{ url('/pricing') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Pricing</a>
                    <a href="{{ url('/demo') }}" class="inline-flex items-center gap-1.5 text-blue-600 dark:text-blue-400 font-semibold hover:underline">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Live Demo
                    </a>
                    <a href="{{ url('/docs') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Docs</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <a href="{{ url('/app/login') }}" class="text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 transition-colors">
                        Sign In
                    </a>
                    <a href="{{ url('/onboarding') }}" class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-sm shadow-blue-600/30 transition-all hover:scale-[1.02]">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800 text-xs text-gray-600 dark:text-gray-400 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">
                <!-- Brand Column -->
                <div class="col-span-2">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-base font-extrabold text-gray-950 dark:text-white">Website Uptime Monitor</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-sm mb-4 leading-relaxed">
                        Mission-critical website monitoring, infrastructure observability, incident automation, and public status pages for global businesses.
                    </p>
                    <div class="text-[11px] text-gray-500">
                        Crafted & Engineered by <a href="https://imrandev.bd" target="_blank" class="font-bold text-gray-900 dark:text-gray-100 hover:text-blue-600">Imran Ahmed</a> (<a href="mailto:me@imrandev.bd" class="hover:underline">me@imrandev.bd</a>).
                    </div>
                </div>

                <!-- Column 2: Monitoring -->
                <div>
                    <h5 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-3">Monitoring</h5>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/features/website-monitoring') }}" class="hover:text-blue-600">Website Uptime</a></li>
                        <li><a href="{{ url('/features/api-monitoring') }}" class="hover:text-blue-600">REST API Monitoring</a></li>
                        <li><a href="{{ url('/features/ssl-monitoring') }}" class="hover:text-blue-600">SSL Certificate</a></li>
                        <li><a href="{{ url('/features/dns-monitoring') }}" class="hover:text-blue-600">DNS Resolution</a></li>
                        <li><a href="{{ url('/features/cron-monitoring') }}" class="hover:text-blue-600">Cron & Heartbeats</a></li>
                        <li><a href="{{ url('/features/server-monitoring') }}" class="hover:text-blue-600">Server Agents</a></li>
                    </ul>
                </div>

                <!-- Column 3: Incident & Status -->
                <div>
                    <h5 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-3">Observability</h5>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/features/incident-management') }}" class="hover:text-blue-600">Incident Lifecycle</a></li>
                        <li><a href="{{ url('/features/alerting') }}" class="hover:text-blue-600">Alert Escalation</a></li>
                        <li><a href="{{ url('/features/public-status-pages') }}" class="hover:text-blue-600">Status Pages</a></li>
                        <li><a href="{{ url('/features/sla-management') }}" class="hover:text-blue-600">SLA & Error Budgets</a></li>
                        <li><a href="{{ url('/features/ai-monitoring') }}" class="hover:text-blue-600">AI Incident Copilot</a></li>
                        <li><a href="{{ url('/features/global-monitoring') }}" class="hover:text-blue-600">Global Probe Network</a></li>
                    </ul>
                </div>

                <!-- Column 4: Platform & Legal -->
                <div>
                    <h5 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-3">Company & Trust</h5>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/about') }}" class="hover:text-blue-600">About Us</a></li>
                        <li><a href="{{ url('/security') }}" class="hover:text-blue-600">Security & SSRF</a></li>
                        <li><a href="{{ url('/privacy') }}" class="hover:text-blue-600">Privacy Policy</a></li>
                        <li><a href="{{ url('/terms') }}" class="hover:text-blue-600">Terms of Service</a></li>
                        <li><a href="{{ url('/contact') }}" class="hover:text-blue-600">Contact Sales</a></li>
                        <li><a href="{{ url('/changelog') }}" class="hover:text-blue-600">Changelog</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-gray-100 dark:border-gray-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-gray-400">
                <div>
                    &copy; {{ date('Y') }} Website Uptime Monitor. All rights reserved. Registered SaaS platform.
                </div>
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        All Global Systems Operational
                    </span>
                    <span>v2.0 Enterprise</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
