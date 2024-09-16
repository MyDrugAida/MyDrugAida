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
    Schema::create('genetic_infos', function (Blueprint $table) {
      $table->id();
      $table->string('patient_uid');
      $table->foreign('patient_uid')->references('firebase_uid')->on('patients')->onDelete('cascade');
      $table->text('genetic_summary');
      $table->timestamps();
    });
  }

  /**
  * Reverse the migrations.
  */
  public function down(): void
  {
    Schema::dropIfExists('genetic_infos');
  }
};