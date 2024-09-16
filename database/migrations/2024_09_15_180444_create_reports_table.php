<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
  * Run the migrations.
  */
  public function up() {
    Schema::create('reports', function (Blueprint $table) {
      $table->id();
      $table->string('patient_uid');
      $table->string('user_uid');
      $table->foreign('user_uid')->references('firebase_uid')->on('users')->onDelete('cascade');
      $table->text('report');
      $table->timestamps();
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