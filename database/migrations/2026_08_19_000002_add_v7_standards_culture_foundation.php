<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qms_taxonomy_terms', function (Blueprint $table) {
            $table->id();
            $table->string('taxonomy')->index();
            $table->string('taxonomy_version')->default('organization-baseline')->index();
            $table->string('code')->index();
            $table->string('label');
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('qms_taxonomy_terms')->nullOnDelete();
            $table->date('effective_from')->nullable()->index();
            $table->date('effective_to')->nullable()->index();
            $table->string('source')->default('Organization')->index();
            $table->string('external_code')->nullable()->index();
            $table->json('mapping')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
            $table->unique(['taxonomy', 'taxonomy_version', 'code'], 'qms_taxonomy_terms_version_code_unique');
        });

        Schema::create('qms_standards', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->string('issuer')->index();
            $table->string('jurisdiction')->default('International')->index();
            $table->string('edition')->nullable()->index();
            $table->string('publication_status')->index();
            $table->date('effective_date')->nullable()->index();
            $table->date('transition_deadline')->nullable()->index();
            $table->string('applicability')->default('Assess per organization scope')->index();
            $table->string('owner')->nullable()->index();
            $table->string('source_url')->nullable();
            $table->string('document_reference')->nullable();
            $table->json('change_history')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_standard_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qms_standard_id')->constrained('qms_standards')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('qms_standard_requirements')->nullOnDelete();
            $table->string('requirement_key')->index();
            $table->string('heading');
            $table->text('internal_interpretation')->nullable();
            $table->json('controls')->nullable();
            $table->json('evidence')->nullable();
            $table->json('mapped_documents')->nullable();
            $table->json('mapped_forms')->nullable();
            $table->json('mapped_risks')->nullable();
            $table->json('mapped_audits')->nullable();
            $table->json('mapped_actions')->nullable();
            $table->string('status')->default('Mapped')->index();
            $table->timestamps();
            $table->unique(['qms_standard_id', 'requirement_key'], 'qms_standard_requirement_unique');
        });

        Schema::create('qms_compliance_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qms_standard_id')->constrained('qms_standards')->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('change_type')->index();
            $table->string('status')->default('Assessment required')->index();
            $table->text('summary');
            $table->json('impacted_areas')->nullable();
            $table->json('actions_required')->nullable();
            $table->date('due_date')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('qms_inspections', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('title');
            $table->string('inspection_type')->index();
            $table->string('station')->nullable()->index();
            $table->string('inspector')->nullable()->index();
            $table->string('status')->default('Planned')->index();
            $table->unsignedInteger('passed_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->unsignedInteger('not_applicable_count')->default(0);
            $table->json('checklist_snapshot')->nullable();
            $table->json('evidence_summary')->nullable();
            $table->date('scheduled_date')->nullable()->index();
            $table->timestamp('completed_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('qms_findings', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('source_type')->index();
            $table->unsignedBigInteger('source_id')->nullable()->index();
            $table->string('source_reference')->nullable()->index();
            $table->string('finding_type')->index();
            $table->string('classification')->nullable()->index();
            $table->string('criterion')->nullable();
            $table->text('objective_evidence')->nullable();
            $table->text('finding_statement');
            $table->string('owner')->nullable()->index();
            $table->string('status')->default('Open')->index();
            $table->timestamps();
        });

        Schema::create('qms_nonconformances', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('requirement_reference')->index();
            $table->text('objective_evidence');
            $table->text('nonconformity_statement');
            $table->string('source')->index();
            $table->string('classification')->index();
            $table->string('severity')->default('Medium')->index();
            $table->text('containment')->nullable();
            $table->text('correction')->nullable();
            $table->string('owner')->nullable()->index();
            $table->date('due_date')->nullable()->index();
            $table->boolean('root_cause_required')->default(true)->index();
            $table->boolean('corrective_action_required')->default(false)->index();
            $table->boolean('effectiveness_required')->default(false)->index();
            $table->string('closure_authority')->nullable();
            $table->string('status')->default('Open')->index();
            $table->timestamps();
        });

        Schema::create('qms_capa_cases', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('source_reference')->nullable()->index();
            $table->string('problem_statement');
            $table->text('containment')->nullable();
            $table->json('root_cause_tools')->nullable();
            $table->text('root_cause_statement')->nullable();
            $table->text('corrective_action_plan')->nullable();
            $table->string('phase')->default('Problem')->index();
            $table->string('owner')->nullable()->index();
            $table->date('due_date')->nullable()->index();
            $table->text('effectiveness_criteria')->nullable();
            $table->date('effectiveness_due_date')->nullable()->index();
            $table->string('effectiveness_result')->nullable()->index();
            $table->string('status')->default('Open')->index();
            $table->timestamps();
        });

        Schema::create('qms_safety_promotions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('title');
            $table->string('source_reference')->nullable()->index();
            $table->text('deidentified_learning');
            $table->json('audience')->nullable();
            $table->string('confidentiality_review')->default('Required')->index();
            $table->string('approval_status')->default('Draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('qms_record_similarities', function (Blueprint $table) {
            $table->id();
            $table->string('source_reference')->index();
            $table->string('candidate_reference')->index();
            $table->unsignedTinyInteger('score')->default(0)->index();
            $table->json('matched_on')->nullable();
            $table->string('decision')->default('Suggested')->index();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable()->index();
            $table->timestamps();
            $table->unique(['source_reference', 'candidate_reference'], 'qms_record_similarity_unique');
        });

        Schema::create('qms_feedback_items', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('context')->nullable()->index();
            $table->string('feedback_type')->index();
            $table->text('message');
            $table->string('status')->default('New')->index();
            $table->string('visibility')->default('Support')->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qms_feedback_items');
        Schema::dropIfExists('qms_record_similarities');
        Schema::dropIfExists('qms_safety_promotions');
        Schema::dropIfExists('qms_capa_cases');
        Schema::dropIfExists('qms_nonconformances');
        Schema::dropIfExists('qms_findings');
        Schema::dropIfExists('qms_inspections');
        Schema::dropIfExists('qms_compliance_changes');
        Schema::dropIfExists('qms_standard_requirements');
        Schema::dropIfExists('qms_standards');
        Schema::dropIfExists('qms_taxonomy_terms');
    }
};
