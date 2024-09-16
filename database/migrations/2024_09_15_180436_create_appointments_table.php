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
    Schema::create('appointments', function (Blueprint $table) {
      $table->id();
      $table->string('patient_uid'); // References Firebase UID of Patient
      $table->string('user_uid'); // References Firebase UID of Doctor/Pharmacist
      $table->dateTime('appointment_date');
      $table->boolean('visibility_to_patient')->default(true);
      $table->boolean('appointment_done')->default(false);
        $table->timestamps();
      });
    }
    /**
    * Reverse the migrations.
    */
    public function down(): void
    {
      Schema::dropIfExists('appointments');
    }
  };