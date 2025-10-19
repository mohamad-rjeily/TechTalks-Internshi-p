<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up(): void
    {
        if (!Schema::hasTable('donations')) return;

        // Make recipient_id nullable
        try { DB::statement('ALTER TABLE donations DROP FOREIGN KEY donations_recipient_id_foreign'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE donations MODIFY recipient_id BIGINT UNSIGNED NULL'); } catch (\Throwable $e) {}

        // Re-add FK allowing NULL
        Schema::table('donations', function (Blueprint $table) {
            try { $table->foreign('recipient_id')->references('id')->on('users')->nullOnDelete(); } catch (\Throwable $e) {}
        });

        // Ensure required columns exist
        Schema::table('donations', function (Blueprint $table) {
            if (!Schema::hasColumn('donations', 'status')) {
                $table->enum('status', ['available','unavailable'])->default('available');
            }
            if (!Schema::hasColumn('donations', 'expiry_date')) {
                $table->date('expiry_date')->nullable(false);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('donations')) return;

        try { DB::statement('ALTER TABLE donations DROP FOREIGN KEY donations_recipient_id_foreign'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE donations MODIFY recipient_id BIGINT UNSIGNED NOT NULL'); } catch (\Throwable $e) {}

        Schema::table('donations', function (Blueprint $table) {
            try { $table->foreign('recipient_id')->references('id')->on('users')->cascadeOnDelete(); } catch (\Throwable $e) {}
        });
    }
};