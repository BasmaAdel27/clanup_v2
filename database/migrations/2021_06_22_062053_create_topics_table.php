<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uid')->unique();
            $table->unsignedBigInteger('topic_category_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();

            $table->foreign('topic_category_id')->references('id')->on('topic_categories')->onDelete('set null');
        });

        Schema::create('topicables', function (Blueprint $table) {
            $table->unsignedBigInteger('topic_id');
            $table->morphs('topicable');
            $table->timestamps();
            
            $table->foreign('topic_id')->references('id')->on('topics')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['topic_id', 'topicable_id', 'topicable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
        Schema::dropIfExists('topicables');
    }
}
