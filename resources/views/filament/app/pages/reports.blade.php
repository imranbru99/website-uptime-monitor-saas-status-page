<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">Export Executive Reliability & Uptime Reports</h3>
            <p class="text-xs text-gray-500 mb-6">Generate publication-ready PDF reports with multilingual Unicode and RTL support powered by <code>imrandevbd/laravel-unicode-pdf</code>.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-xl mb-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Reporting Period</label>
                    <select wire:model="period" class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-2.5">
                        <option value="24h">Last 24 Hours</option>
                        <option value="7d">Last 7 Days</option>
                        <option value="30d">Last 30 Days (Standard)</option>
                        <option value="90d">Last 90 Days (Quarterly SLA)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Report Language & Typography</label>
                    <select wire:model="locale" class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-2.5">
                        <option value="en">English (LTR)</option>
                        <option value="bn">বাংলা / Bengali (Complex Script)</option>
                        <option value="ar">العربية / Arabic (RTL)</option>
                        <option value="hi">हिन्दी / Hindi (Devanagari)</option>
                        <option value="es">Español / Spanish</option>
                        <option value="fr">Français / French</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                <button
                    type="button"
                    wire:click="downloadPdf"
                    class="px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-xs flex items-center gap-2 shadow-sm transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Download Unicode PDF Report</span>
                </button>

                <button
                    type="button"
                    wire:click="downloadCsv"
                    class="px-5 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium text-xs flex items-center gap-2 border border-gray-200 dark:border-gray-700 transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Export Telemetry as CSV</span>
                </button>
            </div>
        </div>
    </div>
</x-filament-panels::page>

