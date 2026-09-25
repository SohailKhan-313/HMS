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
            // Add gender column after 'phone' (or wherever you prefer)
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable()->after('phone');
            
            // Add age column (unsigned integer) after gender
            $table->unsignedInteger('age')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['gender', 'age']);
        });
    }
};