<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecipeIndexing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('recipes', function (Blueprint $table) {
            $table->index('publish_date', 'recipes_publish_date_index');
            $table->index('title', 'recipes_title_index');
            $table->index(['publish_date', 'title'], 'recipes_publish_date_title_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropIndex('recipes_publish_date_index');
            $table->dropIndex('recipes_title_index');
            $table->dropIndex('recipes_publish_date_title_index');
        });
    }
}
