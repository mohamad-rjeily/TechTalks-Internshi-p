<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('donations') || !Schema::hasColumn('donations', 'recipient_id')) {
            return;
        }

        // Drop FK if it exists (typical name)
        try { DB::statement('ALTER TABLE donations DROP FOREIGN KEY donations_recipient_id_foreign'); } catch (\Throwable $e) {}

        // Make column nullable without requiring doctrine/dbal
        try { DB::statement('ALTER TABLE donations MODIFY recipient_id BIGINT UNSIGNED NULL'); } catch (\Throwable $e) {}

        // Re-add FK with NULL on delete
        Schema::table('donations', function (Blueprint $table) {
            try {
                $table->foreign('recipient_id')->references('id')->on('users')->nullOnDelete();
            } catch (\Throwable $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('donations') || !Schema::hasColumn('donations', 'recipient_id')) {
            return;
        }

        try { DB::statement('ALTER TABLE donations DROP FOREIGN KEY donations_recipient_id_foreign'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE donations MODIFY recipient_id BIGINT UNSIGNED NOT NULL'); } catch (\Throwable $e) {}
        Schema::table('donations', function (Blueprint $table) {
            try {
                $table->foreign('recipient_id')->references('id')->on('users')->cascadeOnDelete();
            } catch (\Throwable $e) {}
        });
    }
};
