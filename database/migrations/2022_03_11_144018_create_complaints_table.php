<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('business')->nullable()->default(null);
            $table->string('branch')->nullable()->default(null);
            $table->string('name')->nullable()->default(null);
            $table->string('detail')->nullable()->default(null);
            $table->string('advice')->nullable()->default(null);
            $table->string('status')->nullable()->default(null);
            $table->biginteger('staff_id')->nullable()->default(null);
            $table->boolean('state')->nullable()->default(True);
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
        Schema::dropIfExists('complaints');
    }
}
