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
        Schema::create('medicines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
        
            $table->string('name');
            $table->string('brand');
            $table->string('form', 50);
            $table->string('strength', 50);
            $table->integer('quantity');
            $table->date('expiry_date');
            $table->text('condition_notes');
            $table->string('photo_path');
            $table->enum('status', ['available','requested','donated','expired'])->default('available');
            $table->timestamps();
        
            // Foreign keys (after columns)
            $table->foreign(columns: 'user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
