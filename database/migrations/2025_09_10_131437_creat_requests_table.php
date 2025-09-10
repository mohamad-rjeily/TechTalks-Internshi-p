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
        Schema::create('requests', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('medicine_id'); // Medicine being requested
            $table->unsignedBigInteger('requester_id'); // User who requested
            $table->unsignedBigInteger('donor_id'); // User who owns the medicine
            $table->integer('quantity_requested'); // Quantity requested
            $table->text('message')->nullable(); // Optional message
            $table->enum('status', ['pending', 'accepted', 'declined', 'completed'])->default('pending');
            $table->timestamps(); // created_at and updated_at

            // Foreign key constraints
            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('donor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
