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
            $table->text('description')->nullable();
            $table->longText('cover_image')->nullable()->charset('binary');
            $table->longText('ball_image')->nullable()->charset('binary');
            $table->timestamps();
            $table->softDeletes();
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
