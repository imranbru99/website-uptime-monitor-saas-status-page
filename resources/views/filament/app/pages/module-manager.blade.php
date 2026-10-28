<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Search and Category Filters -->
        <div class="flex flex-col md:flex-row gap-4 justify-between items-center bg-white dark:bg-gray-900 p-4 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="w-full md:w-96">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search 60+ modular capabilities..."
                    class="w-full px-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500"
                />
            </div>

            <!-- Categories Scroll -->
            <div class="flex gap-2 overflow-x-auto w-full md:w-auto pb-1">
                @foreach ($this->categories as $key => $label)
                    <button
                        type="button"
                        wire:click="$set('selectedCategory', '{{ $key }}')"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition-colors {{ $selectedCategory === $key ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Modules Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($this->modules as $mod)
                <div class="flex flex-col justify-between p-5 rounded-xl border transition-all duration-200 {{ $mod->is_active ? 'bg-white dark:bg-gray-900 border-primary-200 dark:border-primary-900 shadow-sm hover:border-primary-400' : 'bg-gray-50 dark:bg-gray-900/40 border-gray-200 dark:border-gray-800 opacity-75' }}">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider {{ $mod->plan_tier === 'enterprise' ? 'bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300' : ($mod->plan_tier === 'business' ? 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300' : ($mod->plan_tier === 'professional' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950 dark:text-indigo-300' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300')) }}">
                                    {{ $mod->plan_tier }}
                                </span>
                                @if ($mod->is_mandatory)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                                        Mandatory
                                    </span>
                                @endif
                            </div>

                            <span class="text-xs px-2 py-1 rounded bg-gray-100 dark:bg-gray-800 text-gray-500 font-mono">
                                v{{ $mod->version }}
                            </span>
                        </div>

                        <h4 class="text-base font-bold text-gray-900 dark:text-white mb-1.5">
                            {{ $mod->name }}
                        </h4>

                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-3 mb-4 leading-relaxed">
                            {{ $mod->description }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium {{ $mod->is_active ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500' }}">
                            <span class="w-2 h-2 rounded-full {{ $mod->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400' }}"></span>
                            {{ $mod->is_active ? 'Enabled' : 'Disabled' }}
                        </span>

                        @if ($mod->is_mandatory)
                            <span class="text-xs text-gray-400 italic">Always Active</span>
                        @else
                            <button
                                type="button"
                                wire:click="toggleModule('{{ $mod->slug }}')"
                                wire:loading.attr="disabled"
                                class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $mod->is_active ? 'bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-950/40 dark:text-red-400' : 'bg-primary-50 text-primary-600 hover:bg-primary-100 dark:bg-primary-950/40 dark:text-primary-400' }}"
                            >
                                <span wire:loading.remove wire:target="toggleModule('{{ $mod->slug }}')">
                                    {{ $mod->is_active ? 'Disable' : 'Enable' }}
                                </span>
                                <span wire:loading wire:target="toggleModule('{{ $mod->slug }}')">
                                    Updating...
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-500">
                    No modules match the filter criteria.
                </div>
            @endforelse
        </div>

        <!-- Recent Activation History -->
        @if ($this->recentActivity->isNotEmpty())
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 shadow-sm mt-8">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4">
                    Recent Module Activation History
                </h3>
                <div class="divide-y divide-gray-100 dark:divide-gray-800 text-xs">
                    @foreach ($this->recentActivity as $activity)
                        <div class="py-2.5 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded font-mono font-medium {{ $activity->action === 'enabled' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' }}">
                                    {{ strtoupper($activity->action) }}
                                </span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $activity->module_slug }}
                                </span>
                            </div>
                            <div class="text-gray-500">
                                by {{ $activity->user?->name ?? 'System' }} • {{ $activity->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
