<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
  * Run the migrations.
  */
  public function up():void {
    Schema::create('consultations', function (Blueprint $table) {
      $table->id();
      $table->string('patient_uid'); // References Firebase UID of Patient
      $table->string('doctor_uid'); // References Firebase UID of Doctor
      $table->text('summary');
      $table->timestamps();
    });
  }

  /**
  * Reverse the migrations.
  */
  public function down(): void
  {
    Schema::dropIfExists('consultations');
  }
};