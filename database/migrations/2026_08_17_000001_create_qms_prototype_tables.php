<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qms_occurrences', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('title');
            $table->string('type');
            $table->string('location');
            $table->string('exact_location')->nullable();
            $table->string('reported_by');
            $table->longText('description');
            $table->string('status')->index();
            $table->string('workflow_stage')->index();
            $table->string('risk_rating')->index();
            $table->boolean('confidential')->default(false);
            $table->timestamp('reported_at')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_actions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('source_reference')->nullable()->index();
            $table->string('title');
            $table->string('owner');
            $table->string('priority')->index();
            $table->string('status')->index();
            $table->date('due_date')->nullable()->index();
            $table->text('evidence')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_investigations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('source_reference')->nullable()->index();
            $table->string('title');
            $table->string('lead_investigator');
            $table->string('status')->index();
            $table->text('scope')->nullable();
            $table->text('findings')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_audits', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('title');
            $table->string('standard')->nullable();
            $table->string('lead_auditor');
            $table->string('status')->index();
            $table->date('scheduled_date')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_risks', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('hazard');
            $table->string('owner');
            $table->string('rating')->index();
            $table->text('controls')->nullable();
            $table->date('review_date')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_documents', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('title');
            $table->string('version');
            $table->string('owner');
            $table->string('status')->index();
            $table->date('review_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qms_documents');
        Schema::dropIfExists('qms_risks');
        Schema::dropIfExists('qms_audits');
        Schema::dropIfExists('qms_investigations');
        Schema::dropIfExists('qms_actions');
        Schema::dropIfExists('qms_occurrences');
    }
};
