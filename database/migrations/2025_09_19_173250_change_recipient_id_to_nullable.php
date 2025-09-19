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
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign(['recipient_id']);

            // 2. Make column nullable
            $table->unsignedBigInteger('recipient_id')->nullable()->change();

            // 3. Re-add foreign key with null on delete
            $table->foreign('recipient_id')
                  ->references('id')->on('users')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign(['recipient_id']);

            // make column NOT NULL again
            $table->unsignedBigInteger('recipient_id')->nullable(false)->change();

            // re-add old constraint
            $table->foreign('recipient_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();
        });
    }
};
