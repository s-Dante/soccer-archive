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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->enum('status', ['visible', 'hidden'])->default('visible');
            $table->timestamp('commented_at')->useCurrent();
            $table->unsignedBigInteger('parent_id')->nullable(); 
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('publication_id')->constrained('publications')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
