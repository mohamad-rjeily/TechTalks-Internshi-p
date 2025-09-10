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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('actor_id'); // User performing the action
            $table->string('action_type', 100); // Action description
            $table->string('target_type', 50); // Type of the object acted on (user, medicine, etc.)
            $table->unsignedBigInteger('target_id'); // ID of the object acted on
            $table->json('details')->nullable(); // Extra data (optional)
            $table->timestamp('created_at')->useCurrent(); // Timestamp when action happened

            // Foreign key constraint
            $table->foreign('actor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
