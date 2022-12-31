<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->string('card_number')->nullable();
            $table->string('card_exp_month')->nullable();
            $table->string('card_exp_year')->nullable();
            $table->double('amount', 15, 4);
            $table->string('currency');
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('receipt')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('set null')->onUpdate('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')
                  ->onDelete('set null')->onUpdate('cascade');
        });

        // Little trick to set auto increments to start from 1234567
        DB::table('orders')->insert(['id' => 1234567, 'amount' => 0, 'currency' => 'USD', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('orders')->where('id', 1234567)->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
