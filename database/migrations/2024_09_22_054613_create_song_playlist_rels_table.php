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
        Schema::create('song_playlist_rels', function (Blueprint $table) {
            // $table->id();

            // Foreign key for the song_code
            $table->string('song_code');
            $table->foreign('song_code')
                ->references('song_code')
                ->on('songs')
                ->onDelete('cascade');

            // Foreign key for the playlist_code
            $table->string('playlist_code');
            $table->foreign('playlist_code')
                ->references('playlist_code')
                ->on('playlists')
                ->onDelete('cascade');
            // $table->timestamps();

            $table->primary(['song_code', 'playlist_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('song_playlist_rels');
    }
};
