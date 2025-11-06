<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Añadimos la columna 'deleted_at' para Soft Deletes.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable()->after('updated_at');
        });
        
        Schema::table('world_cups', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable()->after('updated_at');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable()->after('updated_at');
        });
        
        Schema::table('publications', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable()->after('updated_at');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) { $table->dropColumn('deleted_at'); });
        Schema::table('world_cups', function (Blueprint $table) { $table->dropColumn('deleted_at'); });
        Schema::table('categories', function (Blueprint $table) { $table->dropColumn('deleted_at'); });
        Schema::table('publications', function (Blueprint $table) { $table->dropColumn('deleted_at'); });
        Schema::table('comments', function (Blueprint $table) { $table->dropColumn('deleted_at'); });
    }
};