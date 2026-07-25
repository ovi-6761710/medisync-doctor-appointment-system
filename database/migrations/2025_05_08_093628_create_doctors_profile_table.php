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
        Schema::create('doctors_profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users")->onUpdate("CASCADE")->onDelete("CASCADE");
            $table->string("clinic_name")->nullable();
            $table->text("clinic_address")->nullable();
            $table->longText("clinic_images")->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum("gender", ["male", "female"])->nullable();
            $table->date("dob")->nullable();
            $table->longText("about")->nullable();
            $table->text("profile_image")->nullable();
            $table->text("address")->nullable();
            $table->string("city")->nullable();
            $table->string("state")->nullable();
            $table->string("country")->nullable();
            $table->double("fee")->default(0);
            $table->text("services")->nullable();
            $table->text("specializations")->nullable();
            $table->text("educations")->nullable();
            $table->text("experiences")->nullable();
            $table->text("awards")->nullable();
            $table->double("ratings")->default(0);
            $table->unsignedBigInteger("reviews")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors_profile');
    }
};
