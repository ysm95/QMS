<?php

namespace Tests\Feature;

use App\Models\QmsReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase19UnifiedUxNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_internal_shell_uses_simplified_product_navigation(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $response = $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Home')
            ->assertSee('My Work')
            ->assertSee('Reports')
            ->assertSee('Safety')
            ->assertSee('Quality')
            ->assertSee('Assurance')
            ->assertSee('Analytics')
            ->assertSee('Administration');

        $html = $response->getContent();

        $this->assertStringNotContainsString('Platform Config', $html);
        $this->assertStringNotContainsString('CAPA / Actions</a>', $html);
        $this->assertStringNotContainsString('Admin Center', $html);
    }

    public function test_reports_workspace_has_no_nested_shell_or_permanent_sync(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $response = $this->get('/reporting')
            ->assertOk()
            ->assertSee('Central workspace')
            ->assertSee('Saved views', false)
            ->assertSee('Report list')
            ->assertSee('Report types');

        $html = $response->getContent();

        $this->assertStringNotContainsString('mobile-menu-panel', $html);
        $this->assertStringNotContainsString('sync-bar', $html);
        $this->assertStringNotContainsString('Syncing', $html);
    }

    public function test_report_record_uses_contextual_tabs(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $report = QmsReport::firstOrFail();

        $this->get('/reports/' . $report->id)
            ->assertOk()
            ->assertSee('Report record')
            ->assertSee('Summary')
            ->assertSee('Submission')
            ->assertSee('Comments')
            ->assertSee('Actions')
            ->assertSee('Attachments')
            ->assertSee('Related')
            ->assertSee('History')
            ->assertDontSee('Review queue')
            ->assertDontSee('Screening panel');
    }

    public function test_reporter_navigation_is_four_simple_items(): void
    {
        $this->seed();
        $reporter = User::factory()->create(['email' => 'simple.reporter@qms.test']);
        $reporter->forceFill(['qms_role' => 'Reporter', 'is_active' => true])->save();

        $this->actingAs($reporter)
            ->get('/reporter')
            ->assertOk()
            ->assertSee('Home')
            ->assertSee('Report')
            ->assertSee('My reports')
            ->assertSee('Notifications')
            ->assertDontSee('Administration')
            ->assertDontSee('Screening')
            ->assertDontSee('CAPA');
    }

    public function test_key_user_keeps_same_shell_with_scoped_navigation(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'omar.alharthy@qms.test')->first());

        $response = $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Home')
            ->assertSee('My Work')
            ->assertSee('Safety')
            ->assertDontSee('Administration');

        $html = $response->getContent();

        $this->assertStringNotContainsString('>Quality</a>', $html);
        $this->assertStringNotContainsString('>Assurance</a>', $html);
        $this->assertStringNotContainsString('>Administration</a>', $html);
    }
}
