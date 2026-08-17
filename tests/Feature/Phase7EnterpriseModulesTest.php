<?php

namespace Tests\Feature;

use App\Models\QmsPublicReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase7EnterpriseModulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_enterprise_qms_modules_are_available_and_searchable(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/objectives?search=CAPA')->assertOk()->assertSee('OBJ-2026-00001');
        $this->get('/management-reviews?search=Q3')->assertOk()->assertSee('MR-2026-00001');
        $this->get('/training?search=Auditor')->assertOk()->assertSee('TRN-2026-00045');
        $this->get('/suppliers?search=Training')->assertOk()->assertSee('SUP-2026-00012');
    }

    public function test_public_reporting_portal_accepts_anonymous_confidential_reports(): void
    {
        $this->get('/portal/report')->assertOk()->assertSee('Public reporting portal');

        $this->post('/portal/report', [
            'category' => 'Confidential safety report',
            'location' => 'Ramp area',
            'anonymous' => 1,
            'confidential' => 1,
            'description' => 'Anonymous public report with enough detail for screening.',
        ])->assertRedirect('/portal/report');

        $this->assertDatabaseHas('qms_public_reports', [
            'category' => 'Confidential safety report',
            'anonymous' => true,
            'confidential' => true,
            'reporter_name' => null,
            'status' => 'New',
        ]);

        $this->assertSame(1, QmsPublicReport::count());
    }
}
