<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (! Schema::hasColumn('appointments', 'time')) {
                $table->string('time')->nullable()->after('age');
            }
            if (! Schema::hasColumn('appointments', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (! Schema::hasColumn('appointments', 'diagnosis')) {
                $table->text('diagnosis')->nullable();
            }
            if (! Schema::hasColumn('appointments', 'medicine_suggestions')) {
                $table->text('medicine_suggestions')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['time', 'notes', 'diagnosis', 'medicine_suggestions'] as $column) {
                if (Schema::hasColumn('appointments', $column)) {
                    $columnsToDrop[] = $column;
                }
            }
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
