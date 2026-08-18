<?php

namespace Tests\Feature;

use App\Support\QmsMyWork;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase15MyWorkTest extends TestCase
{
    use RefreshDatabase;

    public function test_my_work_aggregates_real_cross_module_records(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/my-work')
            ->assertOk()
            ->assertSee('My Work')
            ->assertSee('Unified queue')
            ->assertSee('INC-2026-000183')
            ->assertSee('CAPA-2026-00077')
            ->assertSee('INV-2026-00012')
            ->assertSee('AUD-2026-00008')
            ->assertSee('DOC-HSE-001')
            ->assertSee('TRN-2026-00045')
            ->assertSee('SUP-2026-00012');

        $items = QmsMyWork::items();

        $this->assertGreaterThanOrEqual(7, $items->count());
        $this->assertContains('Incident', $items->pluck('module'));
        $this->assertContains('Action', $items->pluck('module'));
        $this->assertContains('Training', $items->pluck('module'));
    }

    public function test_my_work_filters_by_module_and_search_text(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/my-work?module=Incident')
            ->assertOk()
            ->assertSee('INC-2026-000183')
            ->assertDontSee('TRN-2026-00045');

        $this->get('/my-work?search=Supplier')
            ->assertOk()
            ->assertSee('SUP-2026-00012')
            ->assertDontSee('INC-2026-000183');
    }
}
