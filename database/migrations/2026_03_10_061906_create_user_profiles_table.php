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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('profile_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('bio', 120)->nullable();
            $table->string('profession')->nullable();
            $table->string('company')->nullable();
            $table->string('education')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['MALE', 'FEMALE', 'OTHER', 'PREFER_NOT_TO_SAY'])->default('PREFER_NOT_TO_SAY');
            $table->string('relationship_status')->nullable(); 
            $table->string('home_city')->nullable();
            $table->string('current_city')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
