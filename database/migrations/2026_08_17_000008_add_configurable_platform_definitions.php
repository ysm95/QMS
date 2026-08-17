<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qms_form_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedInteger('version')->default(1);
            $table->string('module');
            $table->string('status')->default('Draft')->index();
            $table->json('schema')->nullable();
            $table->text('change_note')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_workflow_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedInteger('version')->default(1);
            $table->string('module');
            $table->string('status')->default('Draft')->index();
            $table->json('stages')->nullable();
            $table->json('rules')->nullable();
            $table->text('change_note')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_saved_views', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('module');
            $table->string('owner');
            $table->json('filters')->nullable();
            $table->boolean('shared')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qms_saved_views');
        Schema::dropIfExists('qms_workflow_definitions');
        Schema::dropIfExists('qms_form_definitions');
    }
};
