<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qms_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('actor')->nullable();
            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->string('reference')->nullable()->index();
            $table->string('action')->index();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('note')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->index(['auditable_type', 'auditable_id']);
        });

        Schema::create('qms_record_notes', function (Blueprint $table) {
            $table->id();
            $table->string('record_type');
            $table->unsignedBigInteger('record_id');
            $table->string('reference')->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author');
            $table->string('visibility')->default('Internal')->index();
            $table->text('body');
            $table->timestamps();
            $table->index(['record_type', 'record_id']);
        });

        Schema::create('qms_record_links', function (Blueprint $table) {
            $table->id();
            $table->string('source_type');
            $table->unsignedBigInteger('source_id');
            $table->string('target_type');
            $table->unsignedBigInteger('target_id');
            $table->string('relationship')->default('Related');
            $table->string('source_reference')->nullable()->index();
            $table->string('target_reference')->nullable()->index();
            $table->timestamps();
            $table->index(['source_type', 'source_id']);
            $table->index(['target_type', 'target_id']);
        });

        Schema::create('qms_compliance_frameworks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('owner');
            $table->string('status')->default('Active')->index();
            $table->json('requirements')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('body');
            $table->string('source_reference')->nullable()->index();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qms_notifications');
        Schema::dropIfExists('qms_compliance_frameworks');
        Schema::dropIfExists('qms_record_links');
        Schema::dropIfExists('qms_record_notes');
        Schema::dropIfExists('qms_audit_logs');
    }
};
