<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            // Columns
            $table->bigIncrements('id');
            $table->string('slug');
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->double('price', 15, 4);
            $table->smallInteger('trial_period')->unsigned()->default(0);
            $table->string('trial_interval')->default('day');
            $table->smallInteger('invoice_period')->unsigned()->default(1);
            $table->string('invoice_interval')->default('month');
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->softDeletes();

            // Indexes
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
