<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qms_email_designs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedInteger('version')->default(1);
            $table->string('status')->default('Draft')->index();
            $table->json('builder_schema')->nullable();
            $table->longText('html_snapshot')->nullable();
            $table->json('variables')->nullable();
            $table->text('change_note')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedInteger('version')->default(1);
            $table->string('module')->index();
            $table->string('status')->default('Draft')->index();
            $table->foreignId('email_design_id')->nullable()->constrained('qms_email_designs')->nullOnDelete();
            $table->string('subject_template');
            $table->text('body_template');
            $table->json('allowed_variables')->nullable();
            $table->text('change_note')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_notification_groups', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('owner')->nullable();
            $table->string('status')->default('Active')->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_notification_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_group_id')->constrained('qms_notification_groups')->cascadeOnDelete();
            $table->string('member_type')->default('user')->index();
            $table->string('member_reference')->index();
            $table->string('display_name');
            $table->timestamps();
        });

        Schema::create('qms_notification_rules', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('module')->index();
            $table->string('event_trigger')->index();
            $table->string('status')->default('Draft')->index();
            $table->foreignId('notification_template_id')->nullable()->constrained('qms_notification_templates')->nullOnDelete();
            $table->json('conditions')->nullable();
            $table->json('recipients')->nullable();
            $table->json('channels')->nullable();
            $table->json('timing')->nullable();
            $table->text('change_note')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_notification_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_rule_id')->nullable()->constrained('qms_notification_rules')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('recipient_display')->nullable();
            $table->string('channel')->index();
            $table->string('status')->default('Queued')->index();
            $table->string('source_reference')->nullable()->index();
            $table->text('failure_reason')->nullable();
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_permission_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('status')->default('Active')->index();
            $table->json('permissions')->nullable();
            $table->json('default_scopes')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_access_scopes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('principal_type')->default('user')->index();
            $table->string('principal_reference')->nullable()->index();
            $table->string('module')->index();
            $table->string('scope_type')->index();
            $table->string('scope_value')->nullable()->index();
            $table->string('status')->default('Active')->index();
            $table->timestamps();
        });

        Schema::create('qms_key_user_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('module')->index();
            $table->string('scope_type')->index();
            $table->string('scope_value')->nullable()->index();
            $table->json('capabilities')->nullable();
            $table->date('effective_from')->nullable();
            $table->date('effective_until')->nullable();
            $table->string('status')->default('Active')->index();
            $table->timestamps();
        });

        Schema::create('qms_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('source_reference')->index();
            $table->string('investigation_reference')->nullable()->index();
            $table->string('finding')->nullable();
            $table->text('root_cause')->nullable();
            $table->text('recommendation');
            $table->text('rationale')->nullable();
            $table->string('priority')->default('Medium')->index();
            $table->string('safety_relevance')->default('Operational')->index();
            $table->string('owner')->nullable()->index();
            $table->string('status')->default('Draft')->index();
            $table->string('approval_decision')->nullable()->index();
            $table->timestamps();
        });

        Schema::table('qms_actions', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
            $table->text('required_outcome')->nullable()->after('description');
            $table->string('responsible_department')->nullable()->after('owner');
            $table->string('risk_relevance')->nullable()->after('priority');
            $table->boolean('evidence_required')->default(false)->after('risk_relevance');
            $table->unsignedTinyInteger('progress')->default(0)->after('status');
            $table->timestamp('assigned_at')->nullable()->after('due_date');
            $table->timestamp('notified_at')->nullable()->after('assigned_at');
            $table->timestamp('viewed_at')->nullable()->after('notified_at');
            $table->timestamp('accepted_at')->nullable()->after('viewed_at');
            $table->timestamp('completed_at')->nullable()->after('accepted_at');
            $table->timestamp('verified_at')->nullable()->after('completed_at');
            $table->timestamp('closed_at')->nullable()->after('verified_at');
            $table->text('extension_reason')->nullable()->after('evidence');
            $table->string('extension_status')->nullable()->after('extension_reason');
            $table->text('verification_note')->nullable()->after('extension_status');
            $table->text('effectiveness_review')->nullable()->after('verification_note');
        });
    }

    public function down(): void
    {
        Schema::table('qms_actions', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'required_outcome',
                'responsible_department',
                'risk_relevance',
                'evidence_required',
                'progress',
                'assigned_at',
                'notified_at',
                'viewed_at',
                'accepted_at',
                'completed_at',
                'verified_at',
                'closed_at',
                'extension_reason',
                'extension_status',
                'verification_note',
                'effectiveness_review',
            ]);
        });

        Schema::dropIfExists('qms_recommendations');
        Schema::dropIfExists('qms_key_user_assignments');
        Schema::dropIfExists('qms_access_scopes');
        Schema::dropIfExists('qms_permission_templates');
        Schema::dropIfExists('qms_notification_deliveries');
        Schema::dropIfExists('qms_notification_rules');
        Schema::dropIfExists('qms_notification_group_members');
        Schema::dropIfExists('qms_notification_groups');
        Schema::dropIfExists('qms_notification_templates');
        Schema::dropIfExists('qms_email_designs');
    }
};
