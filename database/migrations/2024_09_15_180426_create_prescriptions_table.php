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
    Schema::create('prescriptions', function (Blueprint $table) {
      $table->id();
      $table->string('patient_uid'); // References Firebase UID of Patient
      $table->string('doctor_uid'); // References Firebase UID of Doctor
      $table->text('prescription_body_json');
      $table->date('start_date');
      $table->date('end_date');
      $table->boolean('flagged')->default(false);
      $table->string('flagged_by')->default('[]');
        $table->timestamps();
      });
    }

    /**
    * Reverse the migrations.
    */
    public function down(): void
    {
      Schema::dropIfExists('prescriptions');
    }
  };