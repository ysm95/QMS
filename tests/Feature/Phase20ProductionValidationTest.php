<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class Phase20ProductionValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_route_table_has_no_duplicate_names_or_signatures(): void
    {
        $routes = collect(Route::getRoutes())->map(fn ($route) => [
            'name' => $route->getName(),
            'signature' => implode('|', $route->methods()) . ' ' . $route->uri(),
        ]);

        $namedRoutes = $routes->pluck('name')->filter()->values();
        $signatures = $routes->pluck('signature')->values();

        $this->assertSame($namedRoutes->count(), $namedRoutes->unique()->count(), 'Duplicate named routes exist.');
        $this->assertSame($signatures->count(), $signatures->unique()->count(), 'Duplicate method/URI routes exist.');
    }

    public function test_production_environment_and_runbook_are_hardened(): void
    {
        $productionEnv = file_get_contents(base_path('.env.production.example'));
        $deployScript = file_get_contents(base_path('deploy/hostinger_publish_qms.sh'));
        $runbook = file_get_contents(base_path('docs/PRODUCTION_RUNBOOK.md'));

        $this->assertStringContainsString('APP_ENV=production', $productionEnv);
        $this->assertStringContainsString('APP_DEBUG=false', $productionEnv);
        $this->assertStringContainsString('APP_URL=https://qms.ysaidea.com', $productionEnv);
        $this->assertStringContainsString('QUEUE_CONNECTION=database', $productionEnv);

        $this->assertStringContainsString('APP_DEBUG must be false', $deployScript);
        $this->assertStringContainsString('QmsReporterProductSeeder', $deployScript);
        $this->assertStringContainsString('queue:restart', $deployScript);
        $this->assertStringContainsString('migrate --force', $deployScript);

        $this->assertStringContainsString('queue:work', $runbook);
        $this->assertStringContainsString('schedule:run', $runbook);
        $this->assertStringContainsString('mysqldump', $runbook);
        $this->assertStringContainsString('Rollback', $runbook);
    }

    public function test_frontend_pipeline_uses_one_tailwind_vite_setup_without_font_warning(): void
    {
        $viteConfig = file_get_contents(base_path('vite.config.js'));
        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertStringContainsString("tailwindcss from '@tailwindcss/vite'", $viteConfig);
        $this->assertStringContainsString('tailwindcss()', $viteConfig);
        $this->assertStringContainsString('optimizedFallbacks: false', $viteConfig);
        $this->assertStringContainsString("@import 'tailwindcss';", $css);
    }
}
