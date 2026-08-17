<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qms_occurrences', function (Blueprint $table) {
            if (! Schema::hasColumn('qms_occurrences', 'report_key')) {
                $table->string('report_key')->nullable()->after('reference')->index();
            }
            if (! Schema::hasColumn('qms_occurrences', 'event_title')) {
                $table->string('event_title')->nullable()->after('title');
            }
            if (! Schema::hasColumn('qms_occurrences', 'event_date')) {
                $table->date('event_date')->nullable()->after('reported_at')->index();
            }
            if (! Schema::hasColumn('qms_occurrences', 'area_fleet')) {
                $table->string('area_fleet')->nullable()->after('location');
            }
            if (! Schema::hasColumn('qms_occurrences', 'mor')) {
                $table->boolean('mor')->default(false)->after('confidential');
            }
            if (! Schema::hasColumn('qms_occurrences', 'event_categories')) {
                $table->json('event_categories')->nullable()->after('mor');
            }
            if (! Schema::hasColumn('qms_occurrences', 'aircraft_type')) {
                $table->string('aircraft_type')->nullable()->after('event_categories');
            }
            if (! Schema::hasColumn('qms_occurrences', 'aircraft_registration')) {
                $table->string('aircraft_registration')->nullable()->after('aircraft_type');
            }
            if (! Schema::hasColumn('qms_occurrences', 'flight_number')) {
                $table->string('flight_number')->nullable()->after('aircraft_registration');
            }
            if (! Schema::hasColumn('qms_occurrences', 'time_of_occurrence')) {
                $table->time('time_of_occurrence')->nullable()->after('flight_number');
            }
            if (! Schema::hasColumn('qms_occurrences', 'flight_cancelled')) {
                $table->boolean('flight_cancelled')->default(false)->after('time_of_occurrence');
            }
            if (! Schema::hasColumn('qms_occurrences', 'personnel_involved')) {
                $table->json('personnel_involved')->nullable()->after('flight_cancelled');
            }
            if (! Schema::hasColumn('qms_occurrences', 'flight_plan_details')) {
                $table->text('flight_plan_details')->nullable()->after('description');
            }
            if (! Schema::hasColumn('qms_occurrences', 'action_taken')) {
                $table->json('action_taken')->nullable()->after('flight_plan_details');
            }
            if (! Schema::hasColumn('qms_occurrences', 'immediate_corrective_action')) {
                $table->text('immediate_corrective_action')->nullable()->after('action_taken');
            }
            if (! Schema::hasColumn('qms_occurrences', 'feedback_to_reporter')) {
                $table->text('feedback_to_reporter')->nullable()->after('immediate_corrective_action');
            }
        });
    }

    public function down(): void
    {
        Schema::table('qms_occurrences', function (Blueprint $table) {
            foreach ([
                'report_key', 'event_title', 'event_date', 'area_fleet', 'mor', 'event_categories',
                'aircraft_type', 'aircraft_registration', 'flight_number', 'time_of_occurrence',
                'flight_cancelled', 'personnel_involved', 'flight_plan_details', 'action_taken',
                'immediate_corrective_action', 'feedback_to_reporter',
            ] as $column) {
                if (Schema::hasColumn('qms_occurrences', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
