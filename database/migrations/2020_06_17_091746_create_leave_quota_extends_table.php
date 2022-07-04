<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveQuotaExtendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_quota_extends', function (Blueprint $table) {
            $table->unsignedInteger('quota_id')->index()->unique();
            $table->string('employee_no', 10)->index();
            $table->integer('qty');
            $table->integer('used');
            $table->date('expired_at');
            $table->boolean('status');
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
        Schema::dropIfExists('leave_quota_extends');
    }
}
