<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModule extends Command
{
    protected $signature = 'bites:make-module {name}';
    protected $description = 'Make structure for add on of module';

    public function handle()
    {
        $moduleName = $this->argument('name');
        $modulePath = base_path("module/{$moduleName}");

        $directories = [
            'app/Http/Controllers',
            'app/Models',
            'app/Providers',
            'config',
            'database/data',
            'database/factories',
            'database/migrations',
            'database/seeders',
            'resources/assets/js',
            'resources/assets/sass',
            'resources/views/layouts',
            'resources/views',
            'routes',
            'storage',
            'tests/Feature',
            'tests/Unit',
        ];

        foreach ($directories as $directory) {
            $path = "{$modulePath}/{$directory}";
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }
        }

        // Create files
        File::put("{$modulePath}/app/Providers/{$moduleName}ServiceProvider.php", $this->getServiceProviderContent($moduleName));
        File::put("{$modulePath}/app/Providers/RouteServiceProvider.php", $this->getRouteServiceProviderContent($moduleName));
        File::put("{$modulePath}/config/config.php", "<?php\n\nreturn [\n\n];");
        File::put("{$modulePath}/resources/assets/js/app.js", '');
        File::put("{$modulePath}/resources/assets/sass/app.scss", '');
        File::put("{$modulePath}/resources/views/layouts/master.blade.php", '');
        File::put("{$modulePath}/resources/views/index.blade.php", '');
        File::put("{$modulePath}/routes/api.php", "<?php\n\nuse Illuminate\Support\Facades\Route;\n\nRoute::prefix('{$moduleName}')->group(function () {\n    // API routes\n});");
        File::put("{$modulePath}/routes/web.php", "<?php\n\nuse Illuminate\Support\Facades\Route;\n\nRoute::prefix('{$moduleName}')->group(function () {\n    // Web routes\n});");
        File::put("{$modulePath}/composer.json", '{}');
        File::put("{$modulePath}/module.json", '{}');
        File::put("{$modulePath}/package.json", '{}');
        File::put("{$modulePath}/vite.config.js", '');

        $this->info("Module {$moduleName} created successfully.");
    }

    protected function getServiceProviderContent($moduleName)
    {
        return "<?php\n\nnamespace App\\Providers;\n\nuse Illuminate\\Support\\ServiceProvider;\n\nclass {$moduleName}ServiceProvider extends ServiceProvider\n{\n    public function register()\n    {\n        //\n    }\n\n    public function boot()\n    {\n        //\n    }\n}\n";
    }

    protected function getRouteServiceProviderContent($moduleName)
    {
        return "<?php\n\nnamespace App\\Providers;\n\nuse Illuminate\\Foundation\\Support\\Providers\\RouteServiceProvider as ServiceProvider;\nuse Illuminate\\Support\\Facades\\Route;\n\nclass RouteServiceProvider extends ServiceProvider\n{\n    public function map()\n    {\n        \$this->mapWebRoutes();\n        \$this->mapApiRoutes();\n    }\n\n    protected function mapWebRoutes()\n    {\n        Route::middleware('web')\n            ->namespace(\$this->namespace)\n            ->group(base_path('module/{$moduleName}/routes/web.php'));\n    }\n\n    protected function mapApiRoutes()\n    {\n        Route::prefix('api')\n            ->middleware('api')\n            ->namespace(\$this->namespace)\n            ->group(base_path('module/{$moduleName}/routes/api.php'));\n    }\n}\n";
    }
}
