<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLevelTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('level_titles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('level_title_type', ['Managerial', 'Non Managerial']);
            $table->string('level_title_code')->unique();
            $table->string('level_title_name');
            $table->string('level_title_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level_titles');
    }
}
