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
        Schema::create('donations', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('medicine_id'); // Medicine being donated
            $table->unsignedBigInteger('donor_id'); // User who donates
            $table->unsignedBigInteger('recipient_id'); // User who receives
            $table->integer('quantity'); // Quantity donated
            $table->timestamp('confirmed_at')->nullable(); // When donation is confirmed
            $table->text('notes')->nullable(); // Optional notes
            $table->timestamps(); // created_at and updated_at

            // Foreign key constraints
            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
            $table->foreign('donor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
