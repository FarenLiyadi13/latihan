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
        Schema::create('advantage_user', function (Blueprint $table) {
            $table->id();
            // $table->integer('service_id')->nullable();
            $table->foreignId('service_id')->nullable()->index('fk_service_to_advantage_user');
            $table->longText('advantage');
            $table->softDeletes();
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
        Schema::dropIfExists('advantage_user');
    }
};