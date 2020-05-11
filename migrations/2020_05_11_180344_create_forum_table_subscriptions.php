<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumTableSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_subscriptions', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->integer('subscribable_id');
            $table->string('subscribable_type');
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
        Schema::drop('forum_subscriptions');
    }
}
