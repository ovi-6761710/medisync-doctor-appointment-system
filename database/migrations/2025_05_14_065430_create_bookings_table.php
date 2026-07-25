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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("patient_id")->nullable();
            $table->foreign("patient_id")->references("id")->on("users")->onUpdate("CASCADE")->onDelete("CASCADE");
            $table->unsignedBigInteger("doctor_id")->nullable();
            $table->foreign("doctor_id")->references("id")->on("users")->onUpdate("CASCADE")->onDelete("CASCADE");
            $table->string("day")->nullable();
            $table->string("date")->nullable();
            $table->time("from")->nullable();
            $table->time("to")->nullable();
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("email")->nullable();
            $table->string("phone")->nullable();
            $table->text("address")->nullable();
            $table->string("state")->nullable();
            $table->string("city")->nullable();
            $table->string("country")->nullable();
            $table->text("profile_image")->nullable();
            $table->text("doctor")->nullable(); // id, name, email, phone, profile_image
            $table->double("fee")->default(0);
            $table->integer("number")->default(0);
            $table->unsignedBigInteger("invoice_id")->nullable();
            $table->enum("status", ["created", "accepted", "cancelled", "completed"])->default("created");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
