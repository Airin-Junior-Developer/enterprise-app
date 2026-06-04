<?php
// app/Modules/ModuleManager.php
namespace App\Modules;

class ModuleManager
{
    public static function enabled(): array
    {
        return config('modules.enabled', []);
    }

    public static function isEnabled(string $module): bool
    {
        return in_array($module, static::enabled());
    }
}
