<x-filament-panels::page>
    <div class="flex flex-col h-[700px] bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/60 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-primary-100 dark:bg-primary-950/60 text-primary-600 dark:text-primary-400 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">AI Incident Assistant & Diagnostics</h3>
                    <p class="text-xs text-gray-500">Grounded in live monitoring telemetry & incident timelines</p>
                </div>
            </div>

            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                AI Hub Active
            </span>
        </div>

        <!-- Chat History -->
        <div class="flex-1 p-6 overflow-y-auto space-y-4">
            @foreach ($conversation as $msg)
                <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-2xl rounded-2xl px-5 py-3.5 text-sm {{ $msg['role'] === 'user' ? 'bg-primary-600 text-white rounded-br-none' : 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-bl-none border border-gray-200 dark:border-gray-700' }}">
                        <p class="leading-relaxed whitespace-pre-wrap">{{ $msg['content'] }}</p>
                        <span class="block text-[10px] mt-1.5 opacity-60 text-right">{{ $msg['time'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pre-canned Suggestions -->
        <div class="px-6 py-2.5 bg-gray-50 dark:bg-gray-800/40 border-t border-gray-100 dark:border-gray-800/80 flex gap-2 overflow-x-auto text-xs">
            <span class="text-gray-400 font-medium py-1">Quick Prompts:</span>
            <button type="button" wire:click="setQuery('Why is my website down?')" class="px-3 py-1 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-primary-500 hover:text-primary-600 whitespace-nowrap">
                Why is my website down?
            </button>
            <button type="button" wire:click="setQuery('Summarize today\'s incidents and outages')" class="px-3 py-1 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-primary-500 hover:text-primary-600 whitespace-nowrap">
                Summarize recent incidents
            </button>
            <button type="button" wire:click="setQuery('Draft a customer status update for the current incident')" class="px-3 py-1 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-primary-500 hover:text-primary-600 whitespace-nowrap">
                Draft customer status update
            </button>
        </div>

        <!-- Input Bar -->
        <form wire:submit.prevent="ask" class="p-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 flex gap-3">
            <input
                type="text"
                wire:model="query"
                placeholder="Ask about active incidents, probe failures, latency trends..."
                class="flex-1 px-4 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
            <button
                type="submit"
                class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-colors flex items-center gap-2"
            >
                <span>Ask SRE</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>
    </div>
</x-filament-panels::page>
