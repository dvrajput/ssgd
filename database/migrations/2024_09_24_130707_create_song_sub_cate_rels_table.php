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
        Schema::create('song_sub_cate_rels', function (Blueprint $table) {
            $table->id();

            // Foreign key for the song_code
            $table->string('song_code');
            $table->foreign('song_code')
                ->references('song_code')
                ->on('songs')
                ->onDelete('cascade');

            // Foreign key for the sub_category_code
            $table->string('sub_category_code');
            $table->foreign('sub_category_code')
                ->references('sub_category_code')
                ->on('sub_categories')
                ->onDelete('cascade');

            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('song_sub_cate_rels');
    }
};
