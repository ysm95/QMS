<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qms_ai_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('provider_type')->default('Enterprise API');
            $table->string('model_name');
            $table->string('training_scope')->default('Entity-trained approved knowledge');
            $table->string('security_tier')->default('Paid secured enterprise');
            $table->string('data_residency')->nullable();
            $table->boolean('is_approved')->default(false)->index();
            $table->boolean('is_enabled')->default(false)->index();
            $table->text('governance_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('qms_ai_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ai_provider_id')->nullable()->constrained('qms_ai_providers')->nullOnDelete();
            $table->string('module')->index();
            $table->string('source_reference')->nullable()->index();
            $table->string('status')->index();
            $table->text('prompt_summary');
            $table->text('response_summary')->nullable();
            $table->json('controls_applied')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qms_ai_interactions');
        Schema::dropIfExists('qms_ai_providers');
    }
};
