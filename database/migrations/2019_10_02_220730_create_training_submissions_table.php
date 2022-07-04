<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_submissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['External', 'Internal']);
            $table->enum('category', ['Technical', 'Softskill']);
            $table->string('name');
            $table->string('vendor');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('duration', 3, 2);
            $table->text('notes')->nullable();
            $table->tinyInteger('status');
            $table->text('reject_note')->nullable();
            $table->unsignedBigInteger('submit_by')->nullable()->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_submissions');
    }
}
