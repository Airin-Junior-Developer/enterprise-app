<?php
// app/Providers/ModuleServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\ModuleManager;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        foreach (ModuleManager::enabled() as $module) {
            $apiRoutes = app_path("Modules/{$module}/routes/api.php");
            if (file_exists($apiRoutes)) {
                $this->loadRoutesFrom($apiRoutes);
            }

            $migrations = app_path("Modules/{$module}/database/migrations");
            if (is_dir($migrations)) {
                $this->loadMigrationsFrom($migrations);
            }
        }
    }
}
