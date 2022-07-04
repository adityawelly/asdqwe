<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeHKSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_h_k_s', function (Blueprint $table) {
            $table->string('employee_no', 10)->primary();
            $table->foreign('employee_no')->references('registration_number')->on('employees');

            $table->smallInteger('hk');
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
        Schema::dropIfExists('employee_h_k_s');
    }
}
