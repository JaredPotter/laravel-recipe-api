<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->json('tags');
            $table->string('serves')->nullable();
            $table->string('time')->nullable();
            $table->text('description')->nullable();
            $table->json('ingredients');
            $table->json('keyEquipment');
            $table->text('headNote')->nullable();
            $table->json('instructions');
            $table->text('imageUrl')->nullable();
            $table->date('publish_date')->nullable();
            $table->boolean('is_published');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipes');
    }
}
