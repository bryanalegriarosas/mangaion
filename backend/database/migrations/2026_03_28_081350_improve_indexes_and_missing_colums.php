<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ─────────────────────────────────────────────
        // 1. views — índice compuesto [manga_id, created_at]
        //    Crítico para queries de "popular esta semana"
        // ─────────────────────────────────────────────
        Schema::table('views', function (Blueprint $table) {
            $table->index(['manga_id', 'created_at'], 'views_manga_id_created_at_index');
        });

        
        // ─────────────────────────────────────────────
        // 2. chapter_versions — campo published_at
        //    Para ordenar "últimos capítulos" por fecha
        //    de publicación real, no de inserción en BD
        // ─────────────────────────────────────────────
        Schema::table('chapter_versions', function (Blueprint $table) {
            $table->timestamp('published_at')
                  ->nullable()
                  ->after('slug')
                  ->comment('Fecha de publicación real del capítulo');
 
            $table->index('published_at', 'chapter_versions_published_at_index');
        });

        // ─────────────────────────────────────────────
        // 3. users — campo avatar
        //    Referenciado en Home.vue y perfil de usuario
        // ─────────────────────────────────────────────
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')
                  ->nullable()
                  ->after('username')
                  ->comment('Ruta relativa en storage/ o URL externa');
        });

        // ─────────────────────────────────────────────
        // 4. comments — índices en manga_id y chapter_version_id
        //    Para paginación rápida de comentarios
        // ─────────────────────────────────────────────
        Schema::table('comments', function (Blueprint $table) {
            $table->index('manga_id',           'comments_manga_id_index');
            $table->index('chapter_version_id', 'comments_chapter_version_id_index');
        });

        // ─────────────────────────────────────────────
        // 5. ratings — check constraint rango 1–10
        //    PostgreSQL: restricción a nivel de BD
        // ─────────────────────────────────────────────
        DB::statement('
            ALTER TABLE ratings
            ADD CONSTRAINT ratings_rating_range
            CHECK (rating >= 1 AND rating <= 10)
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 5. Eliminar check constraint de ratings
        DB::statement('
            ALTER TABLE ratings
            DROP CONSTRAINT IF EXISTS ratings_rating_range
        ');
 
        // 4. Eliminar índices de comments
        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('comments_manga_id_index');
            $table->dropIndex('comments_chapter_version_id_index');
        });
 
        // 3. Eliminar avatar de users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
 
        // 2. Eliminar published_at de chapter_versions
        Schema::table('chapter_versions', function (Blueprint $table) {
            $table->dropIndex('chapter_versions_published_at_index');
            $table->dropColumn('published_at');
        });
 
        // 1. Eliminar índice compuesto de views
        Schema::table('views', function (Blueprint $table) {
            $table->dropIndex('views_manga_id_created_at_index');
        });
    }
};
