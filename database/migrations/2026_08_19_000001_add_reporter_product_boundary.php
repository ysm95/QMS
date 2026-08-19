<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qms_public_reports', function (Blueprint $table) {
            $table->string('report_type_key')->nullable()->after('reference')->index();
            $table->unsignedBigInteger('reporter_user_id')->nullable()->after('reporter_contact')->index();
            $table->string('receipt_token')->nullable()->unique()->after('status');
            $table->string('public_status')->default('Submitted')->after('receipt_token')->index();
            $table->unsignedInteger('form_version')->default(1)->after('public_status');
            $table->json('submitted_payload')->nullable()->after('description');
            $table->json('client_context')->nullable()->after('submitted_payload');
            $table->text('information_request')->nullable()->after('client_context');
            $table->text('reporter_response')->nullable()->after('information_request');
            $table->timestamp('reporter_response_at')->nullable()->after('reporter_response');
            $table->json('reporter_visible_messages')->nullable()->after('reporter_response_at');
        });

        Schema::create('qms_report_type_rules', function (Blueprint $table) {
            $table->id();
            $table->string('report_type_key')->unique();
            $table->string('title');
            $table->string('type')->index();
            $table->string('module')->index();
            $table->string('priority')->default('Standard')->index();
            $table->text('description')->nullable();
            $table->boolean('published')->default(true)->index();
            $table->boolean('requires_auth')->default(false)->index();
            $table->boolean('supports_anonymous')->default(false);
            $table->json('allowed_roles')->nullable();
            $table->json('allowed_departments')->nullable();
            $table->unsignedInteger('form_version')->default(1);
            $table->date('effective_from')->nullable()->index();
            $table->date('effective_until')->nullable()->index();
            $table->unsignedInteger('sort_order')->default(100);
            $table->string('status')->default('Active')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qms_report_type_rules');

        Schema::table('qms_public_reports', function (Blueprint $table) {
            $table->dropColumn([
                'report_type_key',
                'reporter_user_id',
                'receipt_token',
                'public_status',
                'form_version',
                'submitted_payload',
                'client_context',
                'information_request',
                'reporter_response',
                'reporter_response_at',
                'reporter_visible_messages',
            ]);
        });
    }
};
