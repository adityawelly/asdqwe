<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('place_of_birth', 100);
            $table->date('date_of_birth');
            $table->string('ID_number', 20);
            $table->string('mother_name', 100);
            $table->enum('marital_status', [
                'K', 'K.0', 'K.1', 'K.2', 'K.3', 'T.K'
            ]);
            $table->enum('sex', ['Laki - Laki', 'Perempuan']);
            $table->enum('religion', ['Islam', 'Kristen', 'Katholik', 'Hindu', 'Budha', 'Konghucu']);
            $table->string('phone_number', 20)->nullable();
            $table->string('npwp', 20)->nullable();
            $table->enum('last_education', ['SD', 'SMP', 'SMA', 'SMK', 'D3', 'D4', 'S1', 'S2', 'S3']);
            $table->string('education_focus', 100)->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('employee_id');

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_details');
    }
}
