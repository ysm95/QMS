<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qms_data_sources', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('source_type')->index();
            $table->string('connector')->nullable()->index();
            $table->string('entity')->index();
            $table->string('key_field')->default('id');
            $table->string('display_field');
            $table->json('secondary_display_fields')->nullable();
            $table->json('search_fields')->nullable();
            $table->json('filters')->nullable();
            $table->string('permission_scope')->default('current_user_scope')->index();
            $table->string('organization_scope')->default('default')->index();
            $table->string('cache_policy')->default('indexed_local')->index();
            $table->string('refresh_policy')->default('scheduled')->index();
            $table->unsignedInteger('max_results')->default(50);
            $table->string('failure_policy')->default('show_governed_empty_state');
            $table->string('status')->default('Active')->index();
            $table->text('governance_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_domain_packs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('category')->index();
            $table->string('license_code')->nullable()->index();
            $table->boolean('enabled')->default(false)->index();
            $table->string('status')->default('Planned')->index();
            $table->json('capabilities')->nullable();
            $table->json('shared_engines')->nullable();
            $table->text('governance_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_sync_adapters', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('provider')->index();
            $table->string('purpose')->index();
            $table->string('status')->default('Not configured')->index();
            $table->json('field_mapping')->nullable();
            $table->json('sync_policy')->nullable();
            $table->timestamp('last_success_at')->nullable()->index();
            $table->text('last_error')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_system_monitors', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('area')->index();
            $table->string('status')->default('Ready')->index();
            $table->json('checks')->nullable();
            $table->text('last_result')->nullable();
            $table->timestamp('checked_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('qms_offline_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('module')->index();
            $table->boolean('enabled')->default(false)->index();
            $table->json('allowed_operations')->nullable();
            $table->json('sync_rules')->nullable();
            $table->string('conflict_policy')->default('server_authoritative_review')->index();
            $table->string('status')->default('Design')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qms_offline_profiles');
        Schema::dropIfExists('qms_system_monitors');
        Schema::dropIfExists('qms_sync_adapters');
        Schema::dropIfExists('qms_domain_packs');
        Schema::dropIfExists('qms_data_sources');
    }
};
