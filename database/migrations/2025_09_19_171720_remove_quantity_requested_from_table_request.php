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
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('quantity_requested');
            $table->dropForeign(['donor_id']);

            // 2. Make column nullable
            $table->unsignedBigInteger('donor_id')->nullable()->change();

            // 3. Re-add foreign key with null on delete
            $table->foreign('donor_id')
                  ->references('id')->on('users')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_request', function (Blueprint $table) {
            $table->integer('quantity_requested');
            $table->dropForeign(['donor_id']);

            // make column NOT NULL again
            $table->unsignedBigInteger('donor_id')->nullable(false)->change();

            // re-add old constraint
            $table->foreign('donor_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();
        });
    }
};
