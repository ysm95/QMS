<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qms_report_designs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedInteger('version')->default(1);
            $table->string('module')->index();
            $table->string('status')->default('Draft')->index();
            $table->json('layout')->nullable();
            $table->json('data_sources')->nullable();
            $table->json('output_formats')->nullable();
            $table->text('change_note')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_notification_designs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedInteger('version')->default(1);
            $table->string('module')->index();
            $table->string('event_trigger')->index();
            $table->string('status')->default('Draft')->index();
            $table->json('recipients')->nullable();
            $table->json('conditions')->nullable();
            $table->string('subject_template');
            $table->text('body_template');
            $table->text('change_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qms_notification_designs');
        Schema::dropIfExists('qms_report_designs');
    }
};
