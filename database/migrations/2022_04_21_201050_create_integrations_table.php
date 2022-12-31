<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('logo')->nullable();
            $table->boolean('available_as_plan_feature')->default(0);
            $table->boolean('is_active')->default(1);
            $table->text('data')->nullable();
            $table->timestamps();
        });

        Schema::create('integration_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('integration_id')->nullable();
            $table->string('option');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('integration_id')->references('id')->on('integrations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('integration_settings');
        Schema::dropIfExists('integrations');
    }
}
