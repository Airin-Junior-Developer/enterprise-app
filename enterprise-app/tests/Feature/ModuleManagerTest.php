<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Modules\ModuleManager;

class ModuleManagerTest extends TestCase
{
    public function test_hr_module_is_enabled_by_default(): void
    {
        $this->assertTrue(ModuleManager::isEnabled('HR'));
    }

    public function test_disabled_module_returns_false(): void
    {
        $this->assertFalse(ModuleManager::isEnabled('Saraban'));
    }

    public function test_enabled_returns_array_of_module_names(): void
    {
        $enabled = ModuleManager::enabled();
        $this->assertIsArray($enabled);
        $this->assertContains('HR', $enabled);
    }
}
