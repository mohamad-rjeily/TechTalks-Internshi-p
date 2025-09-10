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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reported_id');
            $table->unsignedBigInteger('target_id'); // can be user or medicine
            $table->enum('target_type', ['user', 'medicine']);
            $table->text('reason');
            $table->enum('status', ['open','resolved'])->default('open');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        
            // Foreign key
            $table->foreign('reported_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
