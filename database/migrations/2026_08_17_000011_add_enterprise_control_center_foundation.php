<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qms_system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->index();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->boolean('is_sensitive')->default(false);
            $table->string('status')->default('Active')->index();
            $table->text('change_note')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_module_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->boolean('enabled')->default(true)->index();
            $table->string('status')->default('Active')->index();
            $table->date('expires_on')->nullable()->index();
            $table->json('limits')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_numbering_rules', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('module')->index();
            $table->string('prefix');
            $table->string('pattern');
            $table->unsignedBigInteger('next_sequence')->default(1);
            $table->boolean('reset_annually')->default(true);
            $table->string('status')->default('Active')->index();
            $table->timestamps();
        });

        Schema::create('qms_configuration_packages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedInteger('version')->default(1);
            $table->string('status')->default('Draft')->index();
            $table->json('payload')->nullable();
            $table->date('effective_date')->nullable()->index();
            $table->text('validation_summary')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_integration_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('correlation_id')->index();
            $table->string('event_type')->index();
            $table->string('source_module')->index();
            $table->string('status')->default('Pending')->index();
            $table->string('idempotency_key')->nullable()->unique();
            $table->json('payload')->nullable();
            $table->unsignedInteger('attempts')->default(0);
            $table->text('last_error')->nullable();
            $table->timestamp('available_at')->nullable()->index();
            $table->timestamp('processed_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('qms_retention_rules', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('module')->index();
            $table->string('classification')->default('Standard')->index();
            $table->unsignedInteger('retention_years')->default(7);
            $table->boolean('legal_hold_allowed')->default(true);
            $table->string('disposition')->default('Archive')->index();
            $table->string('status')->default('Active')->index();
            $table->timestamps();
        });

        Schema::create('qms_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('record_type')->index();
            $table->unsignedBigInteger('record_id')->nullable()->index();
            $table->string('record_reference')->nullable()->index();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('original_name');
            $table->string('stored_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->string('content_hash')->nullable()->index();
            $table->string('classification')->default('Internal')->index();
            $table->string('scan_status')->default('Pending')->index();
            $table->boolean('quarantined')->default(false)->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_electronic_signatures', function (Blueprint $table) {
            $table->id();
            $table->string('record_type')->index();
            $table->unsignedBigInteger('record_id')->nullable()->index();
            $table->string('record_reference')->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('signer_name');
            $table->string('meaning');
            $table->string('record_version')->nullable();
            $table->string('snapshot_hash')->nullable()->index();
            $table->json('auth_context')->nullable();
            $table->text('reason')->nullable();
            $table->timestamp('signed_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qms_electronic_signatures');
        Schema::dropIfExists('qms_attachments');
        Schema::dropIfExists('qms_retention_rules');
        Schema::dropIfExists('qms_integration_events');
        Schema::dropIfExists('qms_configuration_packages');
        Schema::dropIfExists('qms_numbering_rules');
        Schema::dropIfExists('qms_module_licenses');
        Schema::dropIfExists('qms_system_settings');
    }
};
