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
        Schema::table('tags', function (Blueprint $table) {
            $table->string('category')
                  ->nullable()
                  ->after('slug')
                  ->comment('Agrupa los tags en el panel admin: setting, fantasy, creature, character, romance, thematic');
 
            $table->index('category', 'tags_category_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('tags', function (Blueprint $table) {
            $table->dropIndex('tags_category_index');
            $table->dropColumn('category');
        });
    }
};
