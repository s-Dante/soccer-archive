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
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['like', 'dislike']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('publication_id')->constrained('publications')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'publication_id']);
        });
    }

    // public function update(): void
    // {
    //     Schema::table('interactions', function (Blueprint $table) {
    //         $table->enum('type', ['like', 'dislike', 'love', 'angry'])->change();
    //     });
    // }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};
