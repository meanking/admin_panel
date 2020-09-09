<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 60);
            $table->string('lastname', 60);
            $table->string('middlename', 60);
            $table->string('address', 120);
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('country_id');
            $table->char('zip', 10);
            $table->integer('age');
            $table->date('birthdate');
            $table->date('date_hired');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('division_id');
            $table->string('picture', 60);
            $table->date('deleted_at');
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('country_id')->references('id')->on('countries');
            
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('division_id')->references('id')->on('divisions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
