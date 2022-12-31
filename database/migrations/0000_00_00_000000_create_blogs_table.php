<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            // Indexes
            $table->unique('slug');
        });

        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->unsignedBigInteger('blog_category_id')->nullable();
            $table->text('name');
            $table->text('description')->nullable();
            $table->text('content');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->timestamps();

            // Indexes
            $table->unique('slug');

            // Foreign keys
            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('blog_categories');
    }
}
