<?php

namespace Tests\Unit;

use App\Domain\Modules\Models\Module;
use App\Domain\Modules\Services\ModuleManager;
use App\Domain\Tenancy\Models\Tenant;
use Tests\TestCase;

class ModuleManagerTest extends TestCase
{
    public function test_mandatory_modules_cannot_be_disabled(): void
    {
        $tenant = Tenant::create([
            'name' => 'Acme Corp',
            'slug' => 'acme-corp',
        ]);

        $mandatoryModule = Module::create([
            'slug' => 'website_uptime',
            'name' => 'Website Uptime',
            'category' => 'monitoring',
            'description' => 'Mandatory check',
            'is_mandatory' => true,
            'default_enabled' => true,
        ]);

        $result = ModuleManager::disable('website_uptime', $tenant);

        $this->assertFalse($result);
        $this->assertTrue(ModuleManager::isEnabled('website_uptime', $tenant));
    }

    public function test_optional_module_can_be_toggled(): void
    {
        $tenant = Tenant::create([
            'name' => 'Beta Labs',
            'slug' => 'beta-labs',
        ]);

        $optionalModule = Module::create([
            'slug' => 'ai_incident_assistant',
            'name' => 'AI Assistant',
            'category' => 'ai',
            'description' => 'Optional AI module',
            'is_mandatory' => false,
            'default_enabled' => true,
        ]);

        // Disable module
        $disabled = ModuleManager::disable('ai_incident_assistant', $tenant);
        $this->assertTrue($disabled);
        $this->assertFalse(ModuleManager::isEnabled('ai_incident_assistant', $tenant));

        // Re-enable module
        $enabled = ModuleManager::enable('ai_incident_assistant', $tenant);
        $this->assertTrue($enabled);
        $this->assertTrue(ModuleManager::isEnabled('ai_incident_assistant', $tenant));
    }
}
