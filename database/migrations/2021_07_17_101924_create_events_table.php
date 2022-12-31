<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uid');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('slug');
            $table->string('title');
            $table->text('description');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->boolean('is_online')->default(false);
            $table->text('online_meeting_link')->nullable();
            $table->dateTime('rsvp_starts_at')->nullable();
            $table->dateTime('rsvp_ends_at')->nullable();
            $table->integer('attendee_limit')->nullable()->default(0);
            $table->integer('allowed_guests')->nullable()->default(0);
            $table->tinyInteger('status')->default(0);
            $table->dateTime('announced_at')->nullable();
            $table->text('how_to_find_us')->nullable();
            $table->string('rsvp_question')->nullable();
            $table->tinyInteger('fee_method')->nullable();
            $table->unsignedBigInteger('fee_currency_id')->nullable();
            $table->double('fee_amount', 15, 4)->nullable();
            $table->string('fee_additional_refund_policy')->nullable();
            $table->date('cancelled_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('fee_currency_id')->references('id')->on('currencies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
