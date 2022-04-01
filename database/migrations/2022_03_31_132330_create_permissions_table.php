<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role')->nullable()->default(null);
            $table->biginteger('user_id')->nullable()->default(null);
            $table->boolean('status')->nullable()->default(True);
            $table->boolean('view_allcomplaints')->nullable()->default(False);
            $table->boolean('manage_complaints')->nullable()->default(False);
            $table->boolean('view_staff')->nullable()->default(False);
            $table->boolean('view_users')->nullable()->default(False);
            $table->boolean('view_audits')->nullable()->default(False);
            $table->boolean('view_reports')->nullable()->default(False);
            $table->boolean('view_settings')->nullable()->default(False);
            $table->boolean('view_businesses')->nullable()->default(False);
            $table->biginteger('createdby')->nullable()->default(null);
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
        Schema::dropIfExists('permissions');
    }
}
