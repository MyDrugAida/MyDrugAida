<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up():void
{
    Schema::create('patients', function (Blueprint $table) {
        //$table->id();
        $table->string('firebase_uid')->primary(); // Stores Firebase UID
        $table->string('status'); // active, inactive
        $table->string('card_serial')->nullable()->unique();//will be updated later
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
