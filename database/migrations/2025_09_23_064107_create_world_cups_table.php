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
        Schema::create('world_cups', function (Blueprint $table) {
            $table->id();
            $table->year('year')->unique();
            $table->string('host_country');
            $table->string('description')->nullable();
            $table->binary('cover_image')->nullable();
            $table->binary('ball_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('world_cups');
    }
};
