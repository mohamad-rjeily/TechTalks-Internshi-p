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
   
      $table->enum('status', ['available', 'unavailable'])->default('available');

    
      $table->date('expiry_date');
  });
}

public function down(): void
{
  Schema::table('donations', function (Blueprint $table) {
      $table->dropColumn(['status', 'expiry_date']);
  });
}
};
