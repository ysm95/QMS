<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qms_occurrences', function (Blueprint $table) {
            if (! Schema::hasColumn('qms_occurrences', 'sector_to')) {
                $table->string('sector_to')->nullable()->after('area_fleet');
            }
            if (! Schema::hasColumn('qms_occurrences', 'sector_diverted')) {
                $table->string('sector_diverted')->nullable()->after('sector_to');
            }
            if (! Schema::hasColumn('qms_occurrences', 'pilot_name')) {
                $table->string('pilot_name')->nullable()->after('reported_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('qms_occurrences', function (Blueprint $table) {
            foreach (['sector_to', 'sector_diverted', 'pilot_name'] as $column) {
                if (Schema::hasColumn('qms_occurrences', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
