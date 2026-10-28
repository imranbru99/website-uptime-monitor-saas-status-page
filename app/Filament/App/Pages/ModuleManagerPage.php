<?php

namespace App\Filament\App\Pages;

use App\Domain\Modules\Models\Module;
use App\Domain\Modules\Models\ModuleActivityLog;
use App\Domain\Modules\Services\ModuleManager;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ModuleManagerPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Module Manager';

    protected static ?string $title = 'Dynamic Module Manager';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.app.pages.module-manager';

    public string $search = '';

    public string $selectedCategory = 'all';

    public function toggleModule(string $slug): void
    {
        $tenant = auth()->user()?->currentTenant();
        if (! $tenant) {
            Notification::make()->title('No active tenant found')->danger()->send();

            return;
        }

        $module = Module::where('slug', $slug)->first();
        if (! $module) {
            return;
        }

        if ($module->is_mandatory) {
            Notification::make()
                ->title('Mandatory Module')
                ->body('Core system modules cannot be disabled.')
                ->warning()
                ->send();

            return;
        }

        $isCurrentlyEnabled = ModuleManager::isEnabled($slug, $tenant);

        if ($isCurrentlyEnabled) {
            ModuleManager::disable($slug, $tenant, null, auth()->user());
            Notification::make()
                ->title("Module [{$module->name}] Disabled")
                ->body('Module features and schedules have been safely paused.')
                ->warning()
                ->send();
        } else {
            ModuleManager::enable($slug, $tenant, null, auth()->user());
            Notification::make()
                ->title("Module [{$module->name}] Enabled")
                ->body('Module features are now active.')
                ->success()
                ->send();
        }
    }

    public function getCategoriesProperty(): array
    {
        return [
            'all' => 'All Categories',
            'monitoring' => 'Monitoring Essentials',
            'observability' => 'Observability & Agents',
            'incidents' => 'Incident Response',
            'alerts' => 'Alerts & Escalation',
            'status_pages' => 'Status Pages',
            'reliability' => 'SLA & Analytics',
            'ai' => 'AI Operations',
            'agency' => 'Agency & MSP',
            'billing' => 'Billing & Plans',
            'security' => 'Security & Audits',
            'integrations' => 'Integrations & Webhooks',
            'cms' => 'CMS & Marketing',
        ];
    }

    public function getModulesProperty()
    {
        $tenant = auth()->user()?->currentTenant();

        return Module::query()
            ->when($this->selectedCategory !== 'all', fn ($q) => $q->where('category', $this->selectedCategory))
            ->when($this->search, fn ($q) => $q->where(function ($sub) {
                $sub->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%");
            }))
            ->orderBy('is_mandatory', 'desc')
            ->orderBy('is_core', 'desc')
            ->orderBy('name')
            ->get()
            ->map(function ($m) use ($tenant) {
                $m->is_active = $tenant ? ModuleManager::isEnabled($m->slug, $tenant) : (bool) $m->default_enabled;

                return $m;
            });
    }

    public function getRecentActivityProperty()
    {
        $tenant = auth()->user()?->currentTenant();
        if (! $tenant) {
            return collect();
        }

        return ModuleActivityLog::where('tenant_id', $tenant->id)
            ->with(['user'])
            ->latest('created_at')
            ->limit(5)
            ->get();
    }
}
