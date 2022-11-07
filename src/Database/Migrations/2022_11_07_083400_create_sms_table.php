<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xosms_messages', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->text('response')->nullable();
            $table->string('mobile', 20);
            $table->tinyInteger('status'); //0 means added in queue and 1 means delivered
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
        Schema::dropIfExists('sms');
    }
};
