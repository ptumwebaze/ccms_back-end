<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(null);
            $table->string('product')->nullable()->default(null);
            $table->string('branchnumber')->nullable()->default(null);
            $table->string('branch')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('person')->nullable()->default(null);
            $table->string('contact')->nullable()->default(null);
            $table->string('priority')->nullable()->default(null);
            $table->timestamp('startdate')->nullable()->default(null);
            $table->boolean('status')->nullable()->default(True);
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
        Schema::dropIfExists('businesses');
    }
}
