<?php

namespace Tests\Feature;

use App\Models\QmsDataSource;
use App\Models\QmsDomainPack;
use App\Models\QmsOfflineProfile;
use App\Models\QmsSyncAdapter;
use App\Models\QmsSystemMonitor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase13V21EnterpriseGovernanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_v21_enterprise_governance_defaults_are_seeded_and_visible(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/platform')
            ->assertOk()
            ->assertSee('Data source registry')
            ->assertSee('Domain pack matrix')
            ->assertSee('Microsoft Entra Synchronized Users')
            ->assertSee('Aviation Safety and Quality Pack')
            ->assertSee('Quick occurrence reporting offline profile')
            ->assertSee('Controlled AI governance monitor');

        $this->assertDatabaseHas('qms_data_sources', ['code' => 'DS-ENTRA-USERS', 'source_type' => 'Entra Sync']);
        $this->assertDatabaseHas('qms_domain_packs', ['code' => 'PACK-AVIATION', 'enabled' => true]);
        $this->assertDatabaseHas('qms_sync_adapters', ['code' => 'SYNC-ENTRA-USERS', 'status' => 'Not configured']);
        $this->assertDatabaseHas('qms_offline_profiles', ['code' => 'OFF-OCC-QUICK', 'enabled' => false]);
        $this->assertDatabaseHas('qms_system_monitors', ['code' => 'MON-AI', 'status' => 'Blocked']);

        $this->assertSame(4, QmsDataSource::count());
        $this->assertSame(7, QmsDomainPack::count());
        $this->assertSame(1, QmsSyncAdapter::count());
        $this->assertSame(1, QmsOfflineProfile::count());
        $this->assertSame(4, QmsSystemMonitor::count());
    }

    public function test_admin_can_register_data_source_and_domain_pack(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->post('/platform/data-sources', [
            'code' => 'DS-ASSETS',
            'name' => 'Equipment and Assets',
            'source_type' => 'Local Database',
            'connector' => null,
            'entity' => 'qms_equipment',
            'key_field' => 'id',
            'display_field' => 'asset_tag',
            'secondary_display_fields' => 'type, location, serviceability_status',
            'search_fields' => 'asset_tag, serial, location',
            'filters' => 'active:true, serviceable:true',
            'permission_scope' => 'asset_scope',
            'organization_scope' => 'default',
            'cache_policy' => 'indexed_local',
            'refresh_policy' => 'on_change',
            'max_results' => 80,
            'failure_policy' => 'show_governed_empty_state',
            'status' => 'Active',
            'governance_notes' => 'Used by equipment inspection forms.',
        ])->assertRedirect('/platform');

        $this->post('/platform/domain-packs', [
            'code' => 'PACK-HSE-ADV',
            'name' => 'Advanced HSE Operations',
            'category' => 'Future Regulated',
            'license_code' => 'HSE-ADV',
            'enabled' => 0,
            'status' => 'Planned',
            'capabilities' => 'Permit to work, JSA, LOTO, Chemical SDS',
            'shared_engines' => 'Workflow, Actions, Risk, Attachments, Notifications',
            'governance_notes' => 'Reuse shared engines for HSE expansion.',
        ])->assertRedirect('/platform');

        $this->assertDatabaseHas('qms_data_sources', ['code' => 'DS-ASSETS', 'entity' => 'qms_equipment']);
        $this->assertDatabaseHas('qms_domain_packs', ['code' => 'PACK-HSE-ADV', 'license_code' => 'HSE-ADV']);
        $this->assertSame(['asset_tag', 'serial', 'location'], QmsDataSource::where('code', 'DS-ASSETS')->first()->search_fields);
    }
}
