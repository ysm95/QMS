<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qms_objectives', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('title');
            $table->string('owner');
            $table->string('measure');
            $table->string('target');
            $table->string('current_value')->nullable();
            $table->string('period')->default('Monthly');
            $table->string('status')->default('On track')->index();
            $table->date('review_date')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('qms_management_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('title');
            $table->string('chair');
            $table->date('meeting_date')->nullable()->index();
            $table->string('status')->default('Planned')->index();
            $table->json('inputs')->nullable();
            $table->text('decisions')->nullable();
            $table->text('actions_summary')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_training_records', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('person_name');
            $table->string('course');
            $table->string('competency_area');
            $table->date('completed_on')->nullable()->index();
            $table->date('expires_on')->nullable()->index();
            $table->string('status')->default('Current')->index();
            $table->timestamps();
        });

        Schema::create('qms_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name');
            $table->string('category');
            $table->string('owner');
            $table->string('risk_rating')->default('Medium')->index();
            $table->string('status')->default('Approved')->index();
            $table->date('next_review_date')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_public_reports', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('reporter_name')->nullable();
            $table->string('reporter_contact')->nullable();
            $table->string('category');
            $table->string('location')->nullable();
            $table->boolean('anonymous')->default(false)->index();
            $table->boolean('confidential')->default(false)->index();
            $table->string('status')->default('New')->index();
            $table->longText('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qms_public_reports');
        Schema::dropIfExists('qms_suppliers');
        Schema::dropIfExists('qms_training_records');
        Schema::dropIfExists('qms_management_reviews');
        Schema::dropIfExists('qms_objectives');
    }
};
