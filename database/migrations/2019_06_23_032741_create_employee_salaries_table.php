<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('basic_salary', 100)->default('0');
            $table->enum('payroll_type', ['Bulan', 'Hari']);
            $table->enum('meal_allowance', ['Ya', 'Tidak']);
            $table->string('salary_post', 100);
            $table->enum('bank', ['BCA', 'Mandiri'])->nullable();
            $table->string('bank_account_number', 30)->nullable();
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
        Schema::dropIfExists('employee_salaries');
    }
}
