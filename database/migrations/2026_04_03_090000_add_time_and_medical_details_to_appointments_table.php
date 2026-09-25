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
            $table->string('time')->nullable()->after('age');
            $table->text('notes')->nullable()->after('status');
            $table->text('diagnosis')->nullable()->after('notes');
            $table->text('medicine_suggestions')->nullable()->after('diagnosis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['time', 'notes', 'diagnosis', 'medicine_suggestions']);
        });
    }
};
