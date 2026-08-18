<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qms_reports', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('report_key')->nullable()->index();
            $table->string('title');
            $table->string('type')->index();
            $table->string('category')->nullable()->index();
            $table->string('classification')->nullable()->index();
            $table->string('severity')->default('Medium')->index();
            $table->string('status')->default('Submitted')->index();
            $table->string('workflow_stage')->default('Screening')->index();
            $table->string('location')->nullable()->index();
            $table->string('department')->nullable()->index();
            $table->string('reported_by')->nullable()->index();
            $table->foreignId('reporter_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('anonymous')->default(false)->index();
            $table->boolean('confidential')->default(false)->index();
            $table->boolean('mandatory')->default(false)->index();
            $table->longText('description');
            $table->json('payload')->nullable();
            $table->timestamp('reported_at')->nullable()->index();
            $table->timestamp('submitted_at')->nullable()->index();
            $table->timestamp('screened_at')->nullable()->index();
            $table->foreignId('screened_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('screening_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('information_request')->nullable();
            $table->timestamps();

            $table->index(['status', 'workflow_stage']);
            $table->index(['type', 'severity']);
        });

        Schema::create('qms_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('source_report_id')->unique()->constrained('qms_reports')->cascadeOnDelete();
            $table->string('source_report_reference')->index();
            $table->string('title');
            $table->string('type')->index();
            $table->string('classification')->nullable()->index();
            $table->string('severity')->default('Medium')->index();
            $table->string('status')->default('Open')->index();
            $table->string('workflow_stage')->default('Classification')->index();
            $table->string('owner')->nullable()->index();
            $table->string('department')->nullable()->index();
            $table->string('location')->nullable()->index();
            $table->boolean('investigation_required')->default(false)->index();
            $table->boolean('closure_blocked')->default(true)->index();
            $table->json('source_snapshot')->nullable();
            $table->timestamp('accepted_at')->nullable()->index();
            $table->foreignId('accepted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'workflow_stage']);
            $table->index(['type', 'severity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qms_incidents');
        Schema::dropIfExists('qms_reports');
    }
};
